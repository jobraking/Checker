<?php
declare(strict_types=1);

function ocr_png_decode(string $bin): ?array
{
    if (substr($bin, 0, 8) !== "\x89PNG\r\n\x1a\n") {
        return null;
    }
    $p = 8;
    $w = $h = $bd = $ct = $inter = 0;
    $idat = '';
    $plte = '';
    while ($p + 8 <= strlen($bin)) {
        $len = unpack('N', substr($bin, $p, 4))[1];
        $type = substr($bin, $p + 4, 4);
        $data = substr($bin, $p + 8, $len);
        $p += 12 + $len;
        if ($type === 'IHDR') {
            $a = unpack('Nw/Nh/Cbd/Cct/Ccomp/Cfilt/Cint', substr($data, 0, 13));
            $w = $a['w']; $h = $a['h']; $bd = $a['bd']; $ct = $a['ct']; $inter = $a['int'];
        } elseif ($type === 'PLTE') {
            $plte = $data;
        } elseif ($type === 'IDAT') {
            $idat .= $data;
        } elseif ($type === 'IEND') {
            break;
        }
    }
    if ($w <= 0 || $h <= 0 || $bd !== 8 || $inter !== 0) {
        return null;
    }
    $ch = [0 => 1, 2 => 3, 3 => 1, 4 => 2, 6 => 4][$ct] ?? 0;
    if ($ch === 0) {
        return null;
    }
    $raw = zlib_decode($idat);
    if ($raw === false) {
        return null;
    }
    $stride = $w * $ch;
    $pos = 0;
    $prev = str_repeat("\0", $stride);
    $rows = [];
    for ($y = 0; $y < $h; $y++) {
        $f = ord($raw[$pos++]);
        $line = substr($raw, $pos, $stride);
        $pos += $stride;
        $out = '';
        for ($i = 0; $i < $stride; $i++) {
            $a = $i >= $ch ? ord($out[$i - $ch]) : 0;
            $b = ord($prev[$i]);
            $c = $i >= $ch ? ord($prev[$i - $ch]) : 0;
            $x = ord($line[$i]);
            if ($f === 0) {
                $v = $x;
            } elseif ($f === 1) {
                $v = $x + $a;
            } elseif ($f === 2) {
                $v = $x + $b;
            } elseif ($f === 3) {
                $v = $x + (($a + $b) >> 1);
            } else {
                $pp = $a + $b - $c;
                $pa = abs($pp - $a); $pb = abs($pp - $b); $pc = abs($pp - $c);
                $v = $x + (($pa <= $pb && $pa <= $pc) ? $a : (($pb <= $pc) ? $b : $c));
            }
            $out .= chr($v & 255);
        }
        $rows[$y] = $out;
        $prev = $out;
    }
    return ['w' => $w, 'h' => $h, 'ct' => $ct, 'ch' => $ch, 'rows' => $rows, 'plte' => $plte];
}

function ocr_rgb(array $im, int $x, int $y): array
{
    $o = $x * $im['ch'];
    $r = $im['rows'][$y];
    if ($im['ct'] === 2 || $im['ct'] === 6) {
        return [ord($r[$o]), ord($r[$o + 1]), ord($r[$o + 2])];
    }
    if ($im['ct'] === 3) {
        $i = ord($r[$o]);
        return [ord($im['plte'][$i * 3]), ord($im['plte'][$i * 3 + 1]), ord($im['plte'][$i * 3 + 2])];
    }
    $v = ord($r[$o]);
    return [$v, $v, $v];
}

function ocr_mask(array $im): array
{
    $m = [];
    for ($y = 0; $y < $im['h']; $y++) {
        $row = [];
        for ($x = 0; $x < $im['w']; $x++) {
            [$r, $g, $b] = ocr_rgb($im, $x, $y);
            $mx = max($r, $g, $b);
            $mn = min($r, $g, $b);
            $l = intdiv($r * 299 + $g * 587 + $b * 114, 1000);
            $row[] = (($mx - $mn) > 45 || $l < 110) ? 1 : 0;
        }
        $m[] = $row;
    }
    return $m;
}

function ocr_med3(array $m): array
{
    $h = count($m);
    $w = count($m[0]);
    $out = [];
    for ($y = 0; $y < $h; $y++) {
        for ($x = 0; $x < $w; $x++) {
            $c = 0;
            for ($dy = -1; $dy <= 1; $dy++) {
                for ($dx = -1; $dx <= 1; $dx++) {
                    $c += $m[$y + $dy][$x + $dx] ?? 0;
                }
            }
            $out[$y][$x] = $c >= 5 ? 1 : 0;
        }
    }
    return $out;
}

function ocr_cols(array $m): array
{
    $w = count($m[0]);
    $c = array_fill(0, $w, 0);
    foreach ($m as $row) {
        for ($x = 0; $x < $w; $x++) {
            $c[$x] += $row[$x];
        }
    }
    return $c;
}

function ocr_cuts(array $cols, int $w): array
{
    $cuts = [];
    foreach ([30, 52, 73, 95] as $b) {
        $best = $b;
        $bv = PHP_INT_MAX;
        for ($x = max(1, $b - 6); $x <= min($w - 2, $b + 6); $x++) {
            $v = $cols[$x - 1] + $cols[$x] + $cols[$x + 1];
            if ($v < $bv) {
                $bv = $v;
                $best = $x;
            }
        }
        $cuts[] = $best;
    }
    return $cuts;
}

function ocr_norm(array $g, int $tw, int $th): array
{
    $w = $g['x1'] - $g['x0'] + 1;
    $h = $g['y1'] - $g['y0'] + 1;
    $m = $g['mask'];
    $v = [];
    for ($ty = 0; $ty < $th; $ty++) {
        for ($tx = 0; $tx < $tw; $tx++) {
            $sum = 0;
            $cnt = 0;
            $sx0 = $g['x0'] + ($tx * $w) / $tw;
            $sx1 = $g['x0'] + (($tx + 1) * $w) / $tw;
            $sy0 = $g['y0'] + ($ty * $h) / $th;
            $sy1 = $g['y0'] + (($ty + 1) * $h) / $th;
            for ($yy = (int) floor($sy0); $yy < (int) ceil($sy1); $yy++) {
                for ($xx = (int) floor($sx0); $xx < (int) ceil($sx1); $xx++) {
                    $sum += $m[$yy][$xx] ?? 0;
                    $cnt++;
                }
            }
            $v[] = $cnt > 0 ? $sum / $cnt : 0.0;
        }
    }
    return $v;
}

function ocr_glyphs(array $m, array $clean): array
{
    $h = count($m);
    $w = count($m[0]);
    $cols = ocr_cols($m);
    $cuts = ocr_cuts($cols, $w);
    $bounds = array_merge([0], $cuts, [$w]);
    $out = [];
    for ($i = 0; $i < 5; $i++) {
        $x0 = $bounds[$i];
        $x1 = $bounds[$i + 1] - 1;
        $xs = [];
        for ($x = $x0; $x <= $x1; $x++) {
            if ($cols[$x] >= 8) {
                $xs[] = $x;
            }
        }
        if ($xs === []) {
            return [];
        }
        $minx = min($xs);
        $maxx = max($xs);
        $ys = [];
        for ($y = 0; $y < $h; $y++) {
            $c = 0;
            for ($x = $minx; $x <= $maxx; $x++) {
                if ($m[$y][$x]) {
                    $c++;
                }
            }
            if ($c >= 3) {
                $ys[] = $y;
            }
        }
        if ($ys === []) {
            return [];
        }
        $out[] = ['x0' => $minx, 'x1' => $maxx, 'y0' => min($ys), 'y1' => max($ys), 'mask' => $clean];
    }
    return $out;
}

function ocr_templates(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $raw = require __DIR__ . '/ocr-templates.php';
    $bytes = base64_decode((string) $raw['data'], true);
    $labels = (string) $raw['labels'];
    $size = (int) $raw['w'] * (int) $raw['h'];
    $templates = [];
    $n = intdiv(strlen($bytes), $size);
    for ($i = 0; $i < $n; $i++) {
        $v = [];
        $chunk = substr($bytes, $i * $size, $size);
        for ($k = 0; $k < $size; $k++) {
            $v[] = ord($chunk[$k]) / 255.0;
        }
        $templates[] = ['d' => $labels[$i], 'v' => $v];
    }
    $cache = $templates;
    return $cache;
}

function ocr_similarity(array $a, array $b): float
{
    $dot = 0.0;
    $na = 0.0;
    $nb = 0.0;
    $n = count($a);
    for ($k = 0; $k < $n; $k++) {
        $dot += $a[$k] * $b[$k];
        $na += $a[$k] * $a[$k];
        $nb += $b[$k] * $b[$k];
    }
    return $dot / (sqrt($na * $nb) + 1e-9);
}

function ocr_solve_png(string $png, ?float &$confidence = null): ?string
{
    $confidence = null;
    $im = ocr_png_decode($png);
    if ($im === null) {
        return null;
    }
    $m = ocr_mask($im);
    $clean = ocr_med3($m);
    $glyphs = ocr_glyphs($m, $clean);
    if (count($glyphs) !== 5) {
        return null;
    }
    $templates = ocr_templates();
    $out = '';
    $worst = 1.0;
    foreach ($glyphs as $g) {
        $v = ocr_norm($g, 12, 16);
        $best = -1.0;
        $digit = '?';
        foreach ($templates as $t) {
            $s = ocr_similarity($v, $t['v']);
            if ($s > $best) {
                $best = $s;
                $digit = (string) $t['d'];
            }
        }
        $worst = min($worst, $best);
        $out .= $digit;
    }
    $confidence = $worst;
    return preg_match('/^\d{5}$/', $out) ? $out : null;
}

function ocr_solve_data_uri(string $dataUri, ?float &$confidence = null): ?string
{
    $confidence = null;
    $pos = strpos($dataUri, ',');
    if ($pos === false) {
        return null;
    }
    $bin = base64_decode(substr($dataUri, $pos + 1), true);
    if ($bin === false) {
        return null;
    }
    return ocr_solve_png($bin, $confidence);
}

<?php
declare(strict_types=1);

/**
 * PHP port of the ck44 client-side crypto.
 *
 * Two pieces:
 *  - DES-ECB/PKCS7, in pure PHP. This box's OpenSSL 3 does not expose
 *    des-ecb (legacy provider not loaded), so we implement DES directly and
 *    verify it against the NIST known-answer vectors in selftest.php.
 *  - The vendor's RSA. This reproduces jsbn's exact byte packing, including the
 *    chunkSize = 2 * biHighIndex(modulus) quirk (NOT a round multiple of 8),
 *    so the ciphertext is byte-identical to what the browser produces.
 */

const CIPHER_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'; // 61, no Y / j

// ---------------------------------------------------------------- DES tables

const DES_IP = [
    58,50,42,34,26,18,10,2, 60,52,44,36,28,20,12,4,
    62,54,46,38,30,22,14,6, 64,56,48,40,32,24,16,8,
    57,49,41,33,25,17, 9,1, 59,51,43,35,27,19,11,3,
    61,53,45,37,29,21,13, 5, 63,55,47,39,31,23,15,7,
];

const DES_FP = [
    40,8,48,16,56,24,64,32, 39,7,47,15,55,23,63,31,
    38,6,46,14,54,22,62,30, 37,5,45,13,53,21,61,29,
    36,4,44,12,52,20,60,28, 35,3,43,11,51,19,59,27,
    34,2,42,10,50,18,58,26, 33,1,41, 9,49,17,57,25,
];

const DES_E = [
    32, 1, 2, 3, 4, 5,  4, 5, 6, 7, 8, 9,
     8, 9,10,11,12,13, 12,13,14,15,16,17,
    16,17,18,19,20,21, 20,21,22,23,24,25,
    24,25,26,27,28,29, 28,29,30,31,32, 1,
];

const DES_P = [
    16, 7,20,21, 29,12,28,17,  1,15,23,26,  5,18,31,10,
     2, 8,24,14, 32,27, 3, 9, 19,13,30, 6, 22,11, 4,25,
];

const DES_PC1 = [
    57,49,41,33,25,17, 9,  1,58,50,42,34,26,18,
    10, 2,59,51,43,35,27, 19,11, 3,60,52,44,36,
    63,55,47,39,31,23,15,  7,62,54,46,38,30,22,
    14, 6,61,53,45,37,29, 21,13, 5,28,20,12, 4,
];

const DES_PC2 = [
    14,17,11,24, 1, 5,  3,28,15, 6,21,10,
    23,19,12, 4,26, 8, 16, 7,27,20,13, 2,
    41,52,31,37,47,55, 30,40,51,45,33,48,
    44,49,39,56,34,53, 46,42,50,36,29,32,
];

const DES_SHIFTS = [1,1,2,2,2,2,2,2,1,2,2,2,2,2,2,1];

const DES_SBOX = [
    [[14,4,13,1,2,15,11,8,3,10,6,12,5,9,0,7],
     [0,15,7,4,14,2,13,1,10,6,12,11,9,5,3,8],
     [4,1,14,8,13,6,2,11,15,12,9,7,3,10,5,0],
     [15,12,8,2,4,9,1,7,5,11,3,14,10,0,6,13]],
    [[15,1,8,14,6,11,3,4,9,7,2,13,12,0,5,10],
     [3,13,4,7,15,2,8,14,12,0,1,10,6,9,11,5],
     [0,14,7,11,10,4,13,1,5,8,12,6,9,3,2,15],
     [13,8,10,1,3,15,4,2,11,6,7,12,0,5,14,9]],
    [[10,0,9,14,6,3,15,5,1,13,12,7,11,4,2,8],
     [13,7,0,9,3,4,6,10,2,8,5,14,12,11,15,1],
     [13,6,4,9,8,15,3,0,11,1,2,12,5,10,14,7],
     [1,10,13,0,6,9,8,7,4,15,14,3,11,5,2,12]],
    [[7,13,14,3,0,6,9,10,1,2,8,5,11,12,4,15],
     [13,8,11,5,6,15,0,3,4,7,2,12,1,10,14,9],
     [10,6,9,0,12,11,7,13,15,1,3,14,5,2,8,4],
     [3,15,0,6,10,1,13,8,9,4,5,11,12,7,2,14]],
    [[2,12,4,1,7,10,11,6,8,5,3,15,13,0,14,9],
     [14,11,2,12,4,7,13,1,5,0,15,10,3,9,8,6],
     [4,2,1,11,10,13,7,8,15,9,12,5,6,3,0,14],
     [11,8,12,7,1,14,2,13,6,15,0,9,10,4,5,3]],
    [[12,1,10,15,9,2,6,8,0,13,3,4,14,7,5,11],
     [10,15,4,2,7,12,9,5,6,1,13,14,0,11,3,8],
     [9,14,15,5,2,8,12,3,7,0,4,10,1,13,11,6],
     [4,3,2,12,9,5,15,10,11,14,1,7,6,0,8,13]],
    [[4,11,2,14,15,0,8,13,3,12,9,7,5,10,6,1],
     [13,0,11,7,4,9,1,10,14,3,5,12,2,15,8,6],
     [1,4,11,13,12,3,7,14,10,15,6,8,0,5,9,2],
     [6,11,13,8,1,4,10,7,9,5,0,15,14,2,3,12]],
    [[13,2,8,4,6,15,11,1,10,9,3,14,5,0,12,7],
     [1,15,13,8,10,3,7,4,12,5,6,11,0,14,9,2],
     [7,11,4,1,9,12,14,2,0,6,10,13,15,3,5,8],
     [2,1,14,7,4,10,8,13,15,12,9,0,3,5,6,11]],
];

function strToBits(string $s): array
{
    $bits = [];
    for ($i = 0, $n = strlen($s); $i < $n; $i++) {
        $b = ord($s[$i]);
        for ($j = 7; $j >= 0; $j--) {
            $bits[] = ($b >> $j) & 1;
        }
    }
    return $bits;
}

function bitsToStr(array $bits): string
{
    $out = '';
    foreach (array_chunk($bits, 8) as $byte) {
        $v = 0;
        foreach ($byte as $bit) {
            $v = ($v << 1) | $bit;
        }
        $out .= chr($v);
    }
    return $out;
}

function permuteBits(array $bits, array $table): array
{
    $out = [];
    foreach ($table as $pos) {
        $out[] = $bits[$pos - 1];
    }
    return $out;
}

function xorBits(array $a, array $b): array
{
    $out = [];
    foreach ($a as $i => $bit) {
        $out[] = $bit ^ $b[$i];
    }
    return $out;
}

function rotateLeftBits(array $bits, int $n): array
{
    $len = count($bits);
    for ($i = 0; $i < $n; $i++) {
        $bits[] = array_shift($bits);
    }
    return $bits;
}

function desKeySchedule(string $key8): array
{
    $cd = permuteBits(strToBits($key8), DES_PC1);
    $c = array_slice($cd, 0, 28);
    $d = array_slice($cd, 28, 28);
    $sub = [];
    for ($i = 0; $i < 16; $i++) {
        $c = rotateLeftBits($c, DES_SHIFTS[$i]);
        $d = rotateLeftBits($d, DES_SHIFTS[$i]);
        $sub[] = permuteBits(array_merge($c, $d), DES_PC2);
    }
    return $sub;
}

function desEncryptBlock(array $sub, array $block): array
{
    $block = permuteBits($block, DES_IP);
    $l = array_slice($block, 0, 32);
    $r = array_slice($block, 32, 32);

    for ($round = 0; $round < 16; $round++) {
        $expanded = permuteBits($r, DES_E);
        $x = xorBits($expanded, $sub[$round]);

        $sOut = [];
        for ($b = 0; $b < 8; $b++) {
            $c = array_slice($x, $b * 6, 6);
            $row = ($c[0] << 1) | $c[5];
            $col = ($c[1] << 3) | ($c[2] << 2) | ($c[3] << 1) | $c[4];
            $val = DES_SBOX[$b][$row][$col];
            $sOut[] = ($val >> 3) & 1;
            $sOut[] = ($val >> 2) & 1;
            $sOut[] = ($val >> 1) & 1;
            $sOut[] = $val & 1;
        }

        $f = permuteBits($sOut, DES_P);
        $newR = xorBits($l, $f);
        $l = $r;
        $r = $newR;
    }

    return permuteBits(array_merge($r, $l), DES_FP);
}

/** DES-ECB encryption, no padding, raw binary out. */
function desEncryptRaw(string $key8, string $data): string
{
    $sub = desKeySchedule($key8);
    $out = '';
    for ($off = 0, $n = strlen($data); $off < $n; $off += 8) {
        $out .= bitsToStr(desEncryptBlock($sub, strToBits(substr($data, $off, 8))));
    }
    return $out;
}

/**
 * Matches CryptoJS.DES.encrypt(plaintext, key, {mode:ECB, padding:Pkcs7})
 * serialised by CipherParams.toString(), which is the OpenSSL formatter and
 * therefore Base64 -- not hex.
 *
 * $secret16 is the 16-char rndString; CryptoJS's DES keySize is 8 bytes, so
 * only the first 8 characters become the key and the rest is discarded.
 */
function desEncryptBase64(string $plaintext, string $secret16): string
{
    $key = substr($secret16, 0, 8);
    $pad = 8 - (strlen($plaintext) % 8);
    $padded = $plaintext . str_repeat(chr($pad), $pad);
    return base64_encode(desEncryptRaw($key, $padded));
}

// ------------------------------------------------------------------ vendor RSA

/** 16 chars from the vendor alphabet. Uses random_int rather than the
 *  browser's Math.random, which is not a cryptographic RNG anyway. */
function rndString(): string
{
    $t = '';
    for ($i = 0; $i < 16; $i++) {
        $t .= CIPHER_ALPHABET[random_int(0, 60)];
    }
    return $t;
}

/**
 * jsbn's biFromHex + biHighIndex, done on the hex string directly so we do not
 * depend on gmp_strlen() (removed in PHP 8.5) and so the digit scan matches
 * jsbn exactly -- it looks at whole 16-bit digits, not at bit length.
 */
function biHighIndexOf(string $modulusHex): int
{
    $hex = ltrim($modulusHex, ' ');
    if (strlen($hex) % 4 !== 0) {
        $hex = str_repeat('0', 4 - (strlen($hex) % 4)) . $hex;
    }
    $n = intdiv(strlen($hex), 4);
    $hi = $n - 1;
    while ($hi > 0 && hexdec(substr($hex, $hi * 4, 4)) === 0) {
        $hi--;
    }
    return $hi;
}

/**
 * Byte-for-byte equivalent of the vendor's
 *   encryptedString(new RSAKeyPair("10001", "", modulus), reversedSecret)
 *
 * jsbn packs each chunk into 16-bit little-endian digits, computes s^e mod m
 * with Barrett reduction, and emits hex where every retained 16-bit digit is
 * padded to 4 characters (so the output is the hex value left-padded to a
 * multiple of 4, not simply the shortest hex).
 */
function vendorRsaEncrypt(string $plain, string $modulusHex, int $exp = 0x10001): string
{
    $m = gmp_init($modulusHex, 16);
    $chunkSize = 2 * biHighIndexOf($modulusHex); // NB: not a multiple of 8

    $chars = array_values(unpack('C*', $plain));
    while ($chars !== [] && count($chars) % $chunkSize !== 0) {
        $chars[] = 0;
    }

    $out = '';
    $total = count($chars);
    for ($off = 0; $off < $total; $off += $chunkSize) {
        $chunk = array_slice($chars, $off, $chunkSize);
        // digits are little-endian, so the most significant byte comes last
        $s = gmp_import(implode('', array_map('chr', array_reverse($chunk))));
        $c = gmp_powm($s, gmp_init($exp), $m);
        $hex = gmp_strval($c, 16);
        $out .= str_pad($hex, (strlen($hex) + 3) & ~3, '0', STR_PAD_LEFT) . ' ';
    }
    return rtrim($out);
}

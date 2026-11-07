<?php
declare(strict_types=1);

$CK_NODE_REGISTRY = [
    '12xbajee' => ['origin' => 'https://www.12xbajee.com', 'merchant' => 'bet15bdtf6'],
    '1333bet' => ['origin' => 'https://www.1333bet.ai', 'merchant' => 'ckb2bdts2'],
    '1777bet' => ['origin' => 'https://www.1777bet.win', 'merchant' => 'ckbdtf7'],
    '2777betvip' => ['origin' => 'https://www.2777betvip.com', 'merchant' => '2777bdtf2'],
    '299betvip' => ['origin' => 'https://www.299betvip.com', 'merchant' => 'xfbdtf5'],
    '3333bk' => ['origin' => 'https://www.3333bk.com', 'merchant' => '333bdtf7'],
    '399bet02' => ['origin' => 'https://www.399bet02.com', 'merchant' => '399bdtf6'],
    '4777betvip' => ['origin' => 'https://www.4777betvip.com', 'merchant' => '4777bdtf4'],
    '499bet999' => ['origin' => 'https://www.499bet999.com', 'merchant' => '499bdtf6'],
    '5777bet' => ['origin' => 'https://www.5777bet.win', 'merchant' => '3777bdtf2'],
    '6777vip' => ['origin' => 'https://www.vip6777.com', 'merchant' => '6777bdtf4'],
    '8777bet888' => ['origin' => 'https://www.8777bet888.com', 'merchant' => '8777bdts2'],
    '88hbajee' => ['origin' => 'https://www.88hbajee.com', 'merchant' => 'bet08bdtf4'],
    '9777betvip' => ['origin' => 'https://www.9777betvip.com', 'merchant' => '9777bdts2'],
    '97nbajee' => ['origin' => 'https://www.97nbajee.club', 'merchant' => 'bet15bdtf6'],
    'bb44' => ['origin' => 'https://www.bb44.best', 'merchant' => 'fr555bdtf6'],
    'bdt399' => ['origin' => 'https://www.399bdt.com', 'merchant' => '399bdtf6'],
    'bdtk3' => ['origin' => 'https://www.bdtk3.club', 'merchant' => '6i7bdtf1'],
    'bet1333' => ['origin' => 'https://www.1333bet.ai', 'merchant' => 'ckb2bdts2'],
    'bet1777' => ['origin' => 'https://www.1777bet.win', 'merchant' => 'ckbdtf7'],
    'bet39902' => ['origin' => 'https://www.399bet02.com', 'merchant' => '399bdtf6'],
    'bet499' => ['origin' => 'https://www.499bet444.com', 'merchant' => '499bdtf6'],
    'bet499999' => ['origin' => 'https://www.499bet999.com', 'merchant' => '499bdtf6'],
    'bet5666' => ['origin' => 'https://www.5666bet.ai', 'merchant' => '1333bdts2'],
    'bet5777' => ['origin' => 'https://www.5777bet.win', 'merchant' => '3777bdtf2'],
    'bet8777888' => ['origin' => 'https://www.8777bet888.com', 'merchant' => '8777bdts2'],
    'betvip2777' => ['origin' => 'https://www.2777betvip.com', 'merchant' => '2777bdtf2'],
    'betvip299' => ['origin' => 'https://www.299betvip.com', 'merchant' => 'xfbdtf5'],
    'betvip4777' => ['origin' => 'https://www.4777betvip.com', 'merchant' => '4777bdtf4'],
    'betvip9777' => ['origin' => 'https://www.9777betvip.com', 'merchant' => '9777bdts2'],
    'bk3333' => ['origin' => 'https://www.3333bk.com', 'merchant' => '333bdtf7'],
    'cbaji8' => ['origin' => 'https://www.cbaji8.club', 'merchant' => 'bet14bdtf5'],
    'ck44' => ['origin' => 'https://www.ck444.ai', 'merchant' => 'we999bdtf5'],
    'cv666' => ['origin' => 'https://www.cv666app.net', 'merchant' => 'cv666bdtf6'],
    'gbajee53' => ['origin' => 'https://www.g0lbajee.com', 'merchant' => 'bet13bdtf5'],
    'jaya9bangla' => ['origin' => 'https://www.ck444.ai', 'merchant' => 'we999bdtf5'],
    'kx8' => ['origin' => 'https://www.kx8.info', 'merchant' => 'winccbdtf6'],
    'l444' => ['origin' => 'https://www.l444.bet', 'merchant' => '444bdtf5'],
    'nbajee88' => ['origin' => 'https://www.nbajee88.cc', 'merchant' => 'bet15bdtf6'],
    'ppvip10' => ['origin' => 'https://ppvip1.ppvip01.com', 'merchant' => 'ppvipbdtf5'],
    'tk666' => ['origin' => 'https://www.tk666.best', 'merchant' => 'ck555bdtf3'],
    'tk999' => ['origin' => 'https://www.tk999.org', 'merchant' => 'tk999bdtf1'],
    'vip6777' => ['origin' => 'https://www.vip6777.com', 'merchant' => '6777bdtf4'],
];

if (defined('CK_NODE_DIR')) {
    $ckNodeName = basename(CK_NODE_DIR);
    if (!isset($CK_NODE_REGISTRY[$ckNodeName])) {
        http_response_code(500);
        exit('Unknown node');
    }
    if (!defined('CK_NODE')) {
        define('CK_NODE', $ckNodeName);
    }
    if (!defined('CK_ORIGIN')) {
        define('CK_ORIGIN', $CK_NODE_REGISTRY[$ckNodeName]['origin']);
    }
    if (!defined('CK_DEFAULT_MERCHANT')) {
        define('CK_DEFAULT_MERCHANT', $CK_NODE_REGISTRY[$ckNodeName]['merchant']);
    }
}

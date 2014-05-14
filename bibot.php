<?php



include 'bibot.conf.php';
require_once 'libs/KrakenApiClient.php';
$beta   = false;
$url    = $beta ? 'https://api.beta.kraken.com' : 'https://api.kraken.com';
$sslverify = $beta ? false : true;
$version = 0;
$kraken = new KrakenAPI($key, $secret, $url, $version, $sslverify);
// == code ==

$pair   = 'XXBTZEUR';
$res    = $kraken->QueryPublic('Ticker', array('pair' => $pair));
$now    = mktime();
/*
    a = ask array(<price>, <lot volume>),
    b = bid array(<price>, <lot volume>),
    c = last trade closed array(<price>, <lot volume>),
    v = volume array(<today>, <last 24 hours>),
    p = volume weighted average price array(<today>, <last 24 hours>),
    t = number of trades array(<today>, <last 24 hours>),
    l = low array(<today>, <last 24 hours>),
    h = high array(<today>, <last 24 hours>),
    o = today's opening price
*/
foreach ($res['result']['XXBTZEUR'] as $k=>$v) {
    $filePath = $dir.$DS.$k.'.csv';
    pLog($filePath);
    $f = fopen($filePath, 'a+');
    fwrite($f, $now.';'.$v[0].';'.$v[1]."\n" );
    fclose($f);
}



// bitcurex
$bc = json_decode(file_get_contents('https://eur.bitcurex.com/data/ticker.json'));
/*
    [high] => 329.87                h
    [low] => 317.5                  l
    [avg] => 323.685 
    [vwap] => 322.2290352 
    [vol] => 2.12056346             v
    [last] => 322                   c
    [buy] => 320.01                 a
    [sell] => 329.59                b
    [time] => 1400062674 ) 1
*/
$traslate = array(
    'h' => 'high',
    'l' => 'low',
    'v' => 'vol',
    'c' => 'last',
    'a' => 'buy',
    'b' => 'sell'
);
foreach ($traslate as $k=>$v) {
    $filePath = $dir.$DS.''.$k.'.bitcurex.csv';
    pLog($filePath);
    $f = fopen($filePath, 'a+');
    fwrite($f, $bc->time.';'.$bc->$v.';1'."\n" );
    fclose($f);
}


?>
<?php

include 'bibot.conf.php';
require_once 'libs/KrakenApiClient.php';
require_once 'libs/btce-api.php';

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
echo '{ "kraken" : "' . $res['result']['XXBTZEUR']['c'][0] . '", ';

// bitstamp
$bs = json_decode(file_get_contents('https://www.bitstamp.net/api/ticker/'));
$eurusd = json_decode(file_get_contents('https://www.bitstamp.net/api/eur_usd/'));
/*
{"sell": "1.3488", "buy": "1.3599"}
{"high": "616.00", "last": "607.90", "timestamp": "1403097427", "bid": "605.90", "vwap": "607.1", "volume": "8236.56484235", "low": "590.99", "ask": "607.90"}
*/
echo ' "bitstamp" : "' . round($bs->last/$eurusd->buy) . '", ';

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
echo ' "bitcurex" : "' . $bc->last. '", ';

$BTCeAPI = new BTCeAPI(/*API KEY: */ $bt_key,/*API SECRET: */ $bt_sec);
echo ' "btce" : "' . $BTCeAPI->getPairTicker('btc_eur')['ticker']['last'] . '" ';

echo '}';

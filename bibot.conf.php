<?php
// error reporting
error_reporting(E_ALL); ini_set('error_reporting', E_ALL);
/**
 * conf
 */

// kraken
$key    = '';
$secret = '';

// bitcurex
$bc_key = '';
$bc_sec = '';

// -- data --
$dir = 'bibotdata';
$DS = '/';



// log function
function pLog ($msg) {
    echo '&bull; '.$msg.'<br>';
}
// -- eof
?>
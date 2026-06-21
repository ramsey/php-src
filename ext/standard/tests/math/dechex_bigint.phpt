--TEST--
Bigint: dechex() emits signed output and accepts a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Positive bigint (powers of two have clean hex forms: 2**(4k) == 16**k).
var_dump(dechex(2 ** 64) === '10000000000000000');
var_dump(dechex(2 ** 100) === '10000000000000000000000000');
var_dump(dechex(-(2 ** 100)) === '-10000000000000000000000000');

// Signed long output (the legacy unsigned-hex idiom is gone).
echo dechex(-1) . "\n";
echo dechex(255) . "\n";
echo dechex(-255) . "\n";
echo dechex(PHP_INT_MAX) . "\n";
echo dechex(PHP_INT_MIN) . "\n";
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
-1
ff
-ff
7fffffffffffffff
-8000000000000000

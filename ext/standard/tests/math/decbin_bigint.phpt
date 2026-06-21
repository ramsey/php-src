--TEST--
Bigint: decbin() emits signed output and accepts a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php
var_dump(decbin(2 ** 64) === '10000000000000000000000000000000000000000000000000000000000000000');
var_dump(decbin(-(2 ** 64)) === '-10000000000000000000000000000000000000000000000000000000000000000');
var_dump(decbin(PHP_INT_MIN) === '-1000000000000000000000000000000000000000000000000000000000000000');

echo decbin(-1) . "\n";
echo decbin(10) . "\n";
echo decbin(-10) . "\n";
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
-1
1010
-1010

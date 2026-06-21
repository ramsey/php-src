--TEST--
Bigint: decoct() emits signed output and accepts a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php
var_dump(decoct(2 ** 96) === '100000000000000000000000000000000');
var_dump(decoct(-(2 ** 96)) === '-100000000000000000000000000000000');
var_dump(decoct(PHP_INT_MIN) === '-1000000000000000000000');

echo decoct(-1) . "\n";
echo decoct(255) . "\n";
echo decoct(-255) . "\n";
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
-1
377
-377

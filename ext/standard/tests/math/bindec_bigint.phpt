--TEST--
Bigint: bindec() returns a bigint on overflow and parses a sign
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Overflow results in exact bigint instead of a lossy float.
var_dump(bindec(str_repeat('1', 64)) === 2 ** 64 - 1);
var_dump(bindec('1' . str_repeat('0', 64)) === 2 ** 64);
var_dump(bindec('1010') === 10);

// Signed input.
var_dump(bindec('-1010') === -10);
var_dump(bindec('-0b1010') === -10);
var_dump(bindec('+1010') === 10);

// Round-trips with decbin.
var_dump(bindec(decbin(2 ** 70)) === 2 ** 70);
var_dump(bindec(decbin(-(2 ** 70))) === -(2 ** 70));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

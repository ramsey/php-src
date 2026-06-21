--TEST--
Bigint: hexdec() returns a bigint on overflow and parses a sign
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Overflow results in exact bigint instead of a lossy float.
var_dump(hexdec('ffffffffffffffff'));
var_dump(hexdec('10000000000000000'));
var_dump(hexdec('7fffffffffffffff'));
var_dump(hexdec('8000000000000000'));
var_dump(hexdec('7fffffff'));
var_dump(hexdec('80000000'));
var_dump(hexdec('ff') === 255);

// Signed input.
var_dump(hexdec('-ff') === -255);
var_dump(hexdec('-0xff') === -255);
var_dump(hexdec('+ff') === 255);
var_dump(hexdec('-8000000000000000') === -(2 ** 63));
var_dump(hexdec('-8000000000000001') === -(2 ** 63) - 1);
var_dump(hexdec('-80000000') === -(2 ** 31));
var_dump(hexdec('-80000001') === -(2 ** 31) - 1);

// Round-trips with dechex (positive and negative bigints).
var_dump(hexdec(dechex(2 ** 100)) === 2 ** 100);
var_dump(hexdec(dechex(-(2 ** 100))) === -(2 ** 100));

// A non-leading sign is still an invalid character (skipped with a deprecation).
var_dump(@hexdec('f-f') === 255);
?>
--EXPECT--
int(18446744073709551615)
int(18446744073709551616)
int(9223372036854775807)
int(9223372036854775808)
int(2147483647)
int(2147483648)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

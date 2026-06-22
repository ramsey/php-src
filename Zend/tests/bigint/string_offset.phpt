--TEST--
Bigint: a bigint string offset reads as out of range, not a type error
--INI--
opcache.enable_cli=0
--FILE--
<?php

$s = 'abc';

// Reading at a bigint offset is out of range. Treat it like a huge long offset.
var_dump($s[2 ** 70]);
var_dump($s[-(2 ** 70)]);
var_dump($s[PHP_INT_MAX]);
var_dump($s[PHP_INT_MIN]);

// isset() on a bigint offset is false and never throws, just like a huge long offset.
var_dump(isset($s[2 ** 70]));
var_dump(isset($s[-(2 ** 70)]));
var_dump(isset($s[PHP_INT_MAX]));
var_dump(isset($s[PHP_INT_MIN]));

?>
--EXPECTF--
Warning: Uninitialized string offset %d in %s on line %d
string(0) ""

Warning: Uninitialized string offset -%d in %s on line %d
string(0) ""

Warning: Uninitialized string offset %d in %s on line %d
string(0) ""

Warning: Uninitialized string offset -%d in %s on line %d
string(0) ""
bool(false)
bool(false)
bool(false)
bool(false)

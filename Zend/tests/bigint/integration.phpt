--TEST--
Bigint: literal, arithmetic, identity, and rendering together
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--FILE--
<?php
$a = 9223372036854775808;         // literal overflow
$b = PHP_INT_MAX + 1;             // arithmetic overflow
var_dump($a === $b);              // both bigints, equal value
var_dump($a);
var_dump(is_int($b));
var_dump(gettype($b));
var_dump(PHP_INT_MAX + 1 + (-1)); // normalizes back to int
?>
--EXPECT--
bool(true)
int(9223372036854775808)
bool(true)
string(7) "integer"
int(9223372036854775807)

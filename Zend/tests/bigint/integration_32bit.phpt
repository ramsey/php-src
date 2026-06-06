--TEST--
Bigint: literal, arithmetic, identity, and rendering together
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--FILE--
<?php
$a = 2147483648;                  // literal overflow
$b = PHP_INT_MAX + 1;             // arithmetic overflow
var_dump($a === $b);              // both bigints, equal value
var_dump($a);
var_dump(is_int($b));
var_dump(gettype($b));
var_dump(PHP_INT_MAX + 1 + (-1)); // normalizes back to int
?>
--EXPECT--
bool(true)
int(2147483648)
bool(true)
string(7) "integer"
int(2147483647)

--TEST--
Bigint: in-place exponentiation does not leak the result-aliased operand
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// result aliases op1: bigint **= long, result stays a bigint
$x = PHP_INT_MAX + 1;
$x **= 2;
var_dump($x);

// result aliases op1: bigint **= 0 collapses back to a plain int
$y = PHP_INT_MAX + 1;
$y **= 0;
var_dump($y);

// result aliases op1: bigint **= negative exponent degrades to a float
$z = PHP_INT_MAX + 1;
$z **= -1;
var_dump($z);
?>
--EXPECT--
int(4611686018427387904)
int(1)
float(4.656612873077393E-10)

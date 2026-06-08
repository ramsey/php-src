--TEST--
Bigint: in-place division does not leak the result-aliased operand
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// result aliases op1: bigint /= long, exact (demotes back to a long)
$x = (PHP_INT_MAX + 1) * 4;
$x /= 2;
var_dump($x);

// result aliases op1: bigint /= long, inexact (becomes a float)
$y = PHP_INT_MAX + 1;
$y /= 3;
var_dump(is_float($y));

// result aliases op1 and op2: bigint /= itself
$z = (PHP_INT_MAX + 1) * (PHP_INT_MAX + 1);
$z /= $z;
var_dump($z);
?>
--EXPECT--
int(18446744073709551616)
bool(true)
int(1)

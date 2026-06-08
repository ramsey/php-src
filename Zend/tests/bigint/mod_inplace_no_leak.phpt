--TEST--
Bigint: in-place modulo does not leak the result-aliased operand
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// result aliases op1: bigint %= long
$x = (PHP_INT_MAX + 1) * (PHP_INT_MAX + 1);
$x %= 1000;
var_dump($x);

// result aliases op1: bigint %= bigint, remainder stays a bigint
$y = (PHP_INT_MAX + 1) * (PHP_INT_MAX + 1);
$y += PHP_INT_MAX + 1;
$y %= (PHP_INT_MAX + 1) * (PHP_INT_MAX + 1);
var_dump($y);

// result aliases op1 and op2: bigint %= itself is 0
$z = (PHP_INT_MAX + 1) * 5;
$z %= $z;
var_dump($z);
?>
--EXPECT--
int(864)
int(9223372036854775808)
int(0)

--TEST--
intdiv() with bigint with PHP_INT_MIN and -1 does not throw ArithmeticError
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platforms only"); ?>
--FILE--
<?php

// Showcase: PHP_INT_MIN / -1 previously threw ArithmeticError; now returns int(2 ** 63)
var_dump(intdiv(PHP_INT_MIN, -1) === 2 ** 63);
var_dump(intdiv(PHP_INT_MIN, -1));

?>
--EXPECT--
bool(true)
int(9223372036854775808)

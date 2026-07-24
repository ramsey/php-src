--TEST--
bigint: integer multiplication overflow promotes to a boxed integer, unary minus included
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = PHP_INT_MAX * 2;
var_dump(is_int($a));
var_dump(is_float($a));
var_dump(zend_test_int_is_boxed($a));
var_dump($a === zend_test_int_mul(PHP_INT_MAX, 2));
var_dump($a > PHP_INT_MAX);

$x = PHP_INT_MAX;
var_dump(($x * $x) === zend_test_int_mul(PHP_INT_MAX, PHP_INT_MAX));

/* Unary minus compiles to MUL by -1. */
var_dump(zend_test_int_is_boxed(-PHP_INT_MIN));
var_dump(-PHP_INT_MIN === PHP_INT_MAX + 1);

$big = PHP_INT_MAX + 1;
var_dump(-$big === PHP_INT_MIN);
var_dump(zend_test_int_is_boxed(-$big));
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)

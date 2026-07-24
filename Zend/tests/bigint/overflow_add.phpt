--TEST--
bigint: integer addition overflow promotes to a boxed integer, never to float
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = PHP_INT_MAX + 1;
var_dump(is_int($a));
var_dump(is_float($a));
var_dump(zend_test_int_is_boxed($a));
var_dump($a === zend_test_bigint_make((string) PHP_INT_MAX) + 1);
var_dump($a - 1 === PHP_INT_MAX);
var_dump(zend_test_int_is_boxed($a - 1));
var_dump($a > PHP_INT_MAX);

$x = PHP_INT_MAX;
$y = $x + 1;
var_dump($y === $a);
var_dump($y === zend_test_int_add(PHP_INT_MAX, 1));
var_dump(zend_test_int_is_boxed($x + $x));
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)

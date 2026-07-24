--TEST--
bigint: integer subtraction underflow promotes to a boxed integer, never to float
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = PHP_INT_MIN - 1;
var_dump(is_int($a));
var_dump(is_float($a));
var_dump(zend_test_int_is_boxed($a));
var_dump($a + 1 === PHP_INT_MIN);
var_dump(zend_test_int_is_boxed($a + 1));
var_dump($a < PHP_INT_MIN);
var_dump($a === zend_test_int_sub(PHP_INT_MIN, 1));

$x = PHP_INT_MIN;
$y = $x - 1;
var_dump($y === $a);
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)

--TEST--
bigint: increment and decrement cross the long boundary and demote back
--EXTENSIONS--
zend_test
--FILE--
<?php
$x = PHP_INT_MAX;
$x++;
var_dump(is_int($x));
var_dump(zend_test_int_is_boxed($x));
var_dump($x === PHP_INT_MAX + 1);
$x--;
var_dump($x === PHP_INT_MAX);
var_dump(zend_test_int_is_boxed($x));

$y = PHP_INT_MIN;
$y--;
var_dump(zend_test_int_is_boxed($y));
var_dump($y + 1 === PHP_INT_MIN);
$y++;
var_dump($y === PHP_INT_MIN);
var_dump(zend_test_int_is_boxed($y));

/* A numeric string converts exactly, then the increment overflows. */
$s = (string) PHP_INT_MAX;
$s++;
var_dump(zend_test_int_is_boxed($s));
var_dump($s === PHP_INT_MAX + 1);

$t = (string) PHP_INT_MIN;
$t--;
var_dump(zend_test_int_is_boxed($t));
var_dump($t + 1 === PHP_INT_MIN);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
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

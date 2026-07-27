--TEST--
bigint: increment and decrement classify numeric strings through zend_string_to_number
--EXTENSIONS--
zend_test
--FILE--
<?php
// A string that converts exactly to the long boundary; the increment overflows
// into a box.
$x = (string) PHP_INT_MAX;
$x++;
var_dump($x === PHP_INT_MAX + 1);
var_dump(zend_test_int_is_boxed($x));

$x = (string) PHP_INT_MIN;
$x--;
var_dump($x === PHP_INT_MIN - 1);
var_dump(zend_test_int_is_boxed($x));

// A string that is already out of range enters boxed.
$x = (string) (PHP_INT_MAX + 1);
$x++;
var_dump($x === PHP_INT_MAX + 2);
var_dump(zend_test_int_is_boxed($x));

$x = (string) (PHP_INT_MIN - 1);
$x--;
var_dump($x === PHP_INT_MIN - 2);
var_dump(zend_test_int_is_boxed($x));

// Perl-style string increment on a non-numeric string is unchanged.
$x = 'a9';
$x++;
var_dump($x);
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d
string(2) "b0"

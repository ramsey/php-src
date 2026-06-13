--TEST--
Bigint: zend_logical_int_to_long reads a logical int as a long, signaling false when out of range
--EXTENSIONS--
zend_test
--FILE--
<?php
// A plain long passes through.
var_dump(zend_test_logical_int_to_long(5));
var_dump(zend_test_logical_int_to_long(-5));

// A non-canonical in-range bigint (fits a long, but carries the IS_BIGINT tag)
// is read as the equivalent long.
var_dump(zend_test_logical_int_to_long(zend_test_make_bigint('7')));
var_dump(zend_test_logical_int_to_long(zend_test_make_bigint('-7')));
var_dump(zend_test_logical_int_to_long(zend_test_make_bigint('0')));

// The long boundaries fit.
var_dump(zend_test_logical_int_to_long(PHP_INT_MAX) === PHP_INT_MAX);
var_dump(zend_test_logical_int_to_long(PHP_INT_MIN) === PHP_INT_MIN);

// A bigint sitting exactly at the boundary still fits.
var_dump(zend_test_logical_int_to_long(zend_test_make_bigint((string) PHP_INT_MAX)) === PHP_INT_MAX);

// A bigint just past the boundary does not fit: false, and no exception is thrown.
var_dump(zend_test_logical_int_to_long(PHP_INT_MAX + 1));
var_dump(zend_test_logical_int_to_long(PHP_INT_MIN - 1));

// A far out-of-range bigint does not fit either, in both signs.
var_dump(zend_test_logical_int_to_long(2 ** 100));
var_dump(zend_test_logical_int_to_long(-(2 ** 100)));
?>
--EXPECT--
int(5)
int(-5)
int(7)
int(-7)
int(0)
bool(true)
bool(true)
bool(true)
bool(false)
bool(false)
bool(false)
bool(false)

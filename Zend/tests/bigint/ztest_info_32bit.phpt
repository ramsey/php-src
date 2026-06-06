--TEST--
zend_bigint: can_fit_long boundary
--EXTENSIONS--
zend_test
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--FILE--
<?php
var_dump(zend_test_bigint_fits_long('2147483647'));  // PHP_INT_MAX (32-bit)
var_dump(zend_test_bigint_fits_long('2147483648'));  // +1
var_dump(zend_test_bigint_fits_long('-2147483648')); // PHP_INT_MIN (32-bit)
var_dump(zend_test_bigint_fits_long('-2147483649')); // -1
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(false)

--TEST--
zend_bigint: can_fit_long boundary
--EXTENSIONS--
zend_test
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--FILE--
<?php
var_dump(zend_test_bigint_fits_long('9223372036854775807'));  // PHP_INT_MAX (64-bit)
var_dump(zend_test_bigint_fits_long('9223372036854775808'));  // +1
var_dump(zend_test_bigint_fits_long('-9223372036854775808')); // PHP_INT_MIN (64-bit)
var_dump(zend_test_bigint_fits_long('-9223372036854775809')); // -1
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(false)

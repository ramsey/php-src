--TEST--
bigint: Z_PARAM_INT_CLAMP saturates out-of-range values
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_zpp_int_clamp(5));
var_dump(zend_test_zpp_int_clamp(9223372036854775808) === PHP_INT_MAX);
var_dump(zend_test_zpp_int_clamp(-9223372036854775809) === PHP_INT_MIN);
var_dump(zend_test_zpp_int_clamp(zend_test_bigint_make('340282366920938463463374607431768211456')) === PHP_INT_MAX);
var_dump(zend_test_zpp_int_clamp(1e100) === PHP_INT_MAX);
var_dump(zend_test_zpp_int_clamp('5'));
var_dump(zend_test_zpp_int_clamp(5.5));
?>
--EXPECTF--
int(5)
bool(true)
bool(true)
bool(true)
bool(true)
int(5)

Deprecated: Implicit conversion from float 5.5 to int loses precision in %s on line %d
int(5)

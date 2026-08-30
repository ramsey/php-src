--TEST--
bigint: Z_FLF_PARAM_INT_CLAMP saturates without mutating the operand
--EXTENSIONS--
zend_test
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
--FILE--
<?php
var_dump(zend_test_flf_int_clamp(5));
var_dump(zend_test_flf_int_clamp(9223372036854775808) === PHP_INT_MAX);
var_dump(zend_test_flf_int_clamp(-9223372036854775809) === PHP_INT_MIN);
var_dump(zend_test_flf_int_clamp('7'));
var_dump(zend_test_flf_int_clamp('9223372036854775808') === PHP_INT_MAX);
var_dump(zend_test_flf_int_clamp(1e100) === PHP_INT_MAX);

$v = 9223372036854775808;
var_dump(zend_test_flf_int_clamp($v) === PHP_INT_MAX);
var_dump(is_int($v));
var_dump($v > PHP_INT_MAX);

$s = '9223372036854775808';
var_dump(zend_test_flf_int_clamp($s) === PHP_INT_MAX);
var_dump($s === '9223372036854775808');

var_dump(zend_test_flf_int_clamp(5.5));
?>
--EXPECTF--
int(5)
bool(true)
bool(true)
int(7)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

Deprecated: Implicit conversion from float 5.5 to int loses precision in %s on line %d
int(5)

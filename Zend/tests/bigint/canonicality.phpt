--TEST--
bigint canonicality: values that fit long are never boxed
--EXTENSIONS--
zend_test
--FILE--
<?php
$small = zend_test_bigint_make('5');
var_dump($small);
var_dump(zend_test_int_is_boxed($small));
$negmax = zend_test_bigint_make('-9223372036854775808');
var_dump(zend_test_int_is_boxed($negmax) === (PHP_INT_SIZE === 4));
$pow31 = zend_test_bigint_make('2147483648');
var_dump(zend_test_int_is_boxed($pow31) === (PHP_INT_SIZE === 4));
$big = zend_test_bigint_make('9223372036854775808');
var_dump(zend_test_int_is_boxed($big));
?>
--EXPECT--
int(5)
bool(false)
bool(true)
bool(true)
bool(true)

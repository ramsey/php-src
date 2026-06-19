--TEST--
Bigint: array-key conversion helper and readback classifier
--EXTENSIONS--
zend_test
--FILE--
<?php
// zend_bigint_to_str() returns the canonical decimal of a bigint value.
var_dump(zend_test_bigint_to_str(2 ** 100));
var_dump(zend_test_bigint_to_str(-(2 ** 100)));

// zend_array_key_to_zval() classifies a candidate array-key string:
// - a canonical out-of-range decimal is considered an int (i.e., bigint)
// - a canonical in-range decimal is also an int (i.e., long)
// - anything else is a string
var_dump(zend_test_array_key_classify('1267650600228229401496703205376'));
var_dump(zend_test_array_key_classify('-1267650600228229401496703205376'));
var_dump(zend_test_array_key_classify('-5'));
var_dump(zend_test_array_key_classify('00123'));
var_dump(zend_test_array_key_classify('abc'));
var_dump(zend_test_array_key_classify(''));
?>
--EXPECT--
string(31) "1267650600228229401496703205376"
string(32) "-1267650600228229401496703205376"
string(3) "int"
string(3) "int"
string(3) "int"
string(6) "string"
string(6) "string"
string(6) "string"

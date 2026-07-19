--TEST--
bigint backend: parse, render, compare, sign round-trip
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_bigint_roundtrip('123456789012345678901234567890'));
var_dump(zend_test_bigint_roundtrip('-1'));
var_dump(zend_test_bigint_roundtrip('0'));
var_dump(zend_test_bigint_cmp_strings('123456789012345678901234567890', '123456789012345678901234567891'));
var_dump(zend_test_bigint_roundtrip('12x'));
?>
--EXPECT--
string(30) "123456789012345678901234567890"
string(2) "-1"
string(1) "0"
int(-1)
bool(false)

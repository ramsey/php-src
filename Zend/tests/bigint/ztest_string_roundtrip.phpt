--TEST--
zend_bigint: decimal string round-trips through the backend
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_bigint_string_roundtrip('9223372036854775808'));
var_dump(zend_test_bigint_string_roundtrip('-170141183460469231731687303715884105728'));
var_dump(zend_test_bigint_string_roundtrip('0'));
?>
--EXPECT--
string(19) "9223372036854775808"
string(40) "-170141183460469231731687303715884105728"
string(1) "0"

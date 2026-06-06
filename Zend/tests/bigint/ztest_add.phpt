--TEST--
zend_bigint: addition of large values
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_bigint_add_strings('9223372036854775807', '1'));
var_dump(zend_test_bigint_add_strings('9223372036854775808', '9223372036854775808'));
?>
--EXPECT--
string(19) "9223372036854775808"
string(20) "18446744073709551616"

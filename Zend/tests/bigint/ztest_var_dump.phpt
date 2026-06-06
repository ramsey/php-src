--TEST--
IS_BIGINT: var_dump renders int(<decimal>)
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_make_bigint('9223372036854775808'));
var_dump(zend_test_make_bigint('-9223372036854775809'));
debug_zval_dump(zend_test_make_bigint('9223372036854775808'));
?>
--EXPECT--
int(9223372036854775808)
int(-9223372036854775809)
int(9223372036854775808)

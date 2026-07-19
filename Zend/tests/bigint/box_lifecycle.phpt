--TEST--
bigint box: create via zend_test, copy, unset, no leaks
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = zend_test_bigint_make('123456789012345678901234567890');
var_dump(zend_test_int_is_boxed($a));
$b = $a;
var_dump(zend_test_int_is_boxed($b));
var_dump(zend_test_bigint_to_string($a) === '123456789012345678901234567890');
unset($a);
var_dump(zend_test_bigint_to_string($b));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
string(30) "123456789012345678901234567890"

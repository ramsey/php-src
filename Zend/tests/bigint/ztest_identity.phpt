--TEST--
IS_BIGINT: reports as integer to userland
--EXTENSIONS--
zend_test
--FILE--
<?php
$x = zend_test_make_bigint('9223372036854775808');
var_dump(is_int($x));
var_dump(gettype($x));
var_dump(get_debug_type($x));
var_dump(is_scalar($x));
var_dump((bool) $x);
var_dump((bool) zend_test_make_bigint('0'));
?>
--EXPECT--
bool(true)
string(7) "integer"
string(3) "int"
bool(true)
bool(true)
bool(false)

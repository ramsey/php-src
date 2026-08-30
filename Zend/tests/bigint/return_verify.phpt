--TEST--
bigint: boxed returns satisfy declared int return types
--EXTENSIONS--
zend_test
--FILE--
<?php
$big = zend_test_bigint_make('9223372036854775808');
var_dump(is_int($big));
var_dump((string) (new ReflectionFunction('zend_test_bigint_make'))->getReturnType());
var_dump(zend_test_bigint_make('abc'));
$arr = [9223372036854775808 => 1];
var_dump(is_int(array_key_first($arr)));
?>
--EXPECT--
bool(true)
string(9) "int|false"
bool(false)
bool(true)

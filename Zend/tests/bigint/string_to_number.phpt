--TEST--
bigint: zend_string_to_number classifies numeric strings as long, double, or bigint
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_string_to_number('123'));
var_dump(zend_test_string_to_number('9223372036854775807'));
var_dump(zend_test_string_to_number('1.5'));
var_dump(zend_test_string_to_number('1e300'));
var_dump(zend_test_string_to_number('abc'));

$trailing = true;
var_dump(zend_test_string_to_number('  42  ', true, $trailing));
var_dump($trailing);

$v = zend_test_string_to_number('9223372036854775808');
var_dump($v);
var_dump(zend_test_int_is_boxed($v));

$v = zend_test_string_to_number('-9223372036854775809');
var_dump($v);
var_dump(zend_test_int_is_boxed($v));

$v = zend_test_string_to_number('000099999999999999999999');
var_dump($v);
var_dump(zend_test_int_is_boxed($v));

$trailing = false;
$v = zend_test_string_to_number('99999999999999999999abc', true, $trailing);
var_dump($v);
var_dump(zend_test_int_is_boxed($v));
var_dump($trailing);

// Float-shaped strings take their nearest-double value; only pure integer
// strings box.
var_dump(zend_test_string_to_number('99999999999999999999.5'));
var_dump(zend_test_string_to_number('-99999999999999999999.5'));
var_dump(zend_test_string_to_number('99999999999999999999e2'));
var_dump(zend_test_string_to_number('99999999999999999999.'));

$trailing = false;
$v = zend_test_string_to_number('99999999999999999999.5abc', true, $trailing);
var_dump($v);
var_dump($trailing);

$trailing = false;
$v = zend_test_string_to_number('99999999999999999999e', true, $trailing);
var_dump($v);
var_dump($trailing);
?>
--EXPECT--
int(123)
int(9223372036854775807)
float(1.5)
float(1.0E+300)
bool(false)
int(42)
bool(false)
int(9223372036854775808)
bool(true)
int(-9223372036854775809)
bool(true)
int(99999999999999999999)
bool(true)
int(99999999999999999999)
bool(true)
bool(true)
float(1.0E+20)
float(-1.0E+20)
float(1.0E+22)
float(1.0E+20)
float(1.0E+20)
bool(true)
float(1.0E+20)
bool(true)

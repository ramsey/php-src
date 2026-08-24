--TEST--
bigint: conversions of boxed integers: float and string exact, bool true, intval identity
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$neg = zend_test_bigint_make('-340282366920938463463374607431768211456');
var_dump((float) $pos === 2.0 ** 128);
var_dump((string) $pos);
var_dump((bool) $neg);
var_dump(intval($pos) === $pos);
var_dump(intval($neg, 10) === $neg);

$arr = (array) $pos;
var_dump(count($arr) === 1);
var_dump(array_key_exists(0, $arr));
var_dump(zend_test_int_is_boxed($arr[0]));
var_dump(zend_test_bigint_to_string($arr[0]) === '340282366920938463463374607431768211456');

$obj = (object) $neg;
var_dump($obj instanceof stdClass);
var_dump(zend_test_int_is_boxed($obj->scalar));
var_dump(zend_test_bigint_to_string($obj->scalar) === '-340282366920938463463374607431768211456');
?>
--EXPECT--
bool(true)
string(39) "340282366920938463463374607431768211456"
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

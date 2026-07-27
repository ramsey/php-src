--TEST--
bigint: arithmetic on out-of-range numeric strings stays exact
--EXTENSIONS--
zend_test
--FILE--
<?php
$s = (string) (PHP_INT_MAX + 1);
$r = $s + 0;
var_dump($r === PHP_INT_MAX + 1);
var_dump(zend_test_int_is_boxed($r));

$r = '99999999999999999999' * '99999999999999999999';
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$r = '99999999999999999999' - 1;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$r = '340282366920938463463374607431768211456' / 2;
var_dump($r);
var_dump(is_int($r));
var_dump(zend_test_int_is_boxed($r));

$r = 2 ** '64';
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$r = '99999999999999999999' + 0.5;
var_dump($r);
var_dump(is_float($r));

$r = ' 000099999999999999999999 ' + 0;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$x = (string) (PHP_INT_MAX + 1);
$x += 1;
var_dump($x === PHP_INT_MAX + 2);
var_dump(zend_test_int_is_boxed($x));
?>
--EXPECT--
bool(true)
bool(true)
int(9999999999999999999800000000000000000001)
bool(true)
int(99999999999999999998)
bool(true)
int(170141183460469231731687303715884105728)
bool(true)
bool(true)
int(18446744073709551616)
bool(true)
float(1.0E+20)
bool(true)
int(99999999999999999999)
bool(true)
bool(true)
bool(true)

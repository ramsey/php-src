--TEST--
bigint: modulo and bitwise operators promote out-of-range numeric strings
--EXTENSIONS--
zend_test
--FILE--
<?php
$r = '99999999999999999999' % 7;
var_dump($r);

$r = 7 % '99999999999999999999';
var_dump($r);

$r = '18446744073709551616' | 1;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$r = '18446744073709551616' & -1;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$r = '18446744073709551616' ^ 0;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

$r = '99999999999999999999' << 1;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

var_dump('abc' & 'abd');
?>
--EXPECT--
int(1)
int(7)
int(18446744073709551617)
bool(true)
int(18446744073709551616)
bool(true)
int(18446744073709551616)
bool(true)
int(199999999999999999998)
bool(true)
string(3) "ab`"

--TEST--
bigint: bitwise operators over boxed operands match the value ops
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = zend_test_int_shift_left(1, 100);
$b = zend_test_int_shift_left(1, 90);

var_dump(($a | $b) === zend_test_int_or($a, $b));
var_dump(($a & $b) === zend_test_int_and($a, $b));
var_dump(($a ^ $b) === zend_test_int_xor($a, $b));
var_dump((~$a) === zend_test_int_not($a));

var_dump(($a | 5) === zend_test_int_or($a, 5));
var_dump(($a & 5) === 0);
var_dump(($a & 0) === 0);

var_dump(is_int(~$a));
var_dump(zend_test_int_is_boxed(~$a));
var_dump((~$a) < 0);

/* A box against its own complement covers every bit. */
var_dump(($a & ~$a) === 0);
var_dump(($a | ~$a) === -1);
?>
--EXPECT--
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
bool(true)
bool(true)

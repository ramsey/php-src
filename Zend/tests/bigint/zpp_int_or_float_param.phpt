--TEST--
Bigint: Z_PARAM_INT_OR_FLOAT accepts IS_LONG, IS_DOUBLE, IS_BIGINT at full precision (weak mode)
--EXTENSIONS--
zend_test
--FILE--
<?php
// long passes through unchanged
var_dump(zend_test_zpp_int_or_float(5));

// double passes through unchanged
var_dump(zend_test_zpp_int_or_float(2.5));

// bigint passes at full precision
$big = 2 ** 100;
var_dump(zend_test_zpp_int_or_float($big) === $big);

// weak: numeric string coerces to float
var_dump(zend_test_zpp_int_or_float('2.5'));

// weak: numeric string coerces to int
var_dump(zend_test_zpp_int_or_float('5'));

// non-numeric string results in a TypeError
try {
    zend_test_zpp_int_or_float('abc');
} catch (TypeError $e) {
    echo get_class($e) . ": " . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(5)
float(2.5)
bool(true)
float(2.5)
int(5)
TypeError: zend_test_zpp_int_or_float(): Argument #1 ($n) must be of type int|float, string given

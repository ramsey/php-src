--TEST--
Bigint: Z_PARAM_INT_OR_FLOAT in strict mode accepts IS_LONG, IS_DOUBLE, IS_BIGINT, rejects strings
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);

// long passes in strict mode
var_dump(zend_test_zpp_int_or_float(5));

// double passes in strict mode
var_dump(zend_test_zpp_int_or_float(2.5));

// bigint passes at full precision in strict mode
$big = 2 ** 100;
var_dump(zend_test_zpp_int_or_float($big) === $big);

// numeric string results in TypeError in strict mode
try {
    zend_test_zpp_int_or_float('5');
} catch (TypeError $e) {
    echo get_class($e) . ": " . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(5)
float(2.5)
bool(true)
TypeError: zend_test_zpp_int_or_float(): Argument #1 ($n) must be of type int|float, string given

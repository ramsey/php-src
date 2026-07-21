--TEST--
bigint value op: unary minus and abs, including the LONG_MIN boundary
--EXTENSIONS--
zend_test
--FILE--
<?php
$big = zend_test_bigint_make('90000000000000000000');
$neg = zend_test_int_neg($big);
echo zend_test_bigint_to_string($neg) . "\n";
var_dump(zend_test_int_is_boxed(zend_test_int_abs($neg)));
echo zend_test_bigint_to_string(zend_test_int_abs($neg)) . "\n";

$min = zend_test_bigint_make((string) PHP_INT_MIN);
$absMin = zend_test_int_abs($min);
var_dump(zend_test_int_is_boxed($absMin));
$backToMin = zend_test_int_neg($absMin);
var_dump($backToMin === PHP_INT_MIN);
var_dump(zend_test_int_is_boxed(zend_test_int_neg(PHP_INT_MIN)));

var_dump(zend_test_int_neg(5));
var_dump(zend_test_int_abs(-5));

try {
    zend_test_int_neg('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_abs('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
-90000000000000000000
bool(true)
90000000000000000000
bool(true)
bool(true)
bool(true)
int(-5)
int(5)
TypeError: zend_test_int_neg(): Argument #1 ($value) must be an integer
TypeError: zend_test_int_abs(): Argument #1 ($value) must be an integer

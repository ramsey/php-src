--TEST--
bigint value op: comparisons, sign, parity, bit length, double conversions
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = zend_test_bigint_make('100000000000000000000');
$b = zend_test_bigint_make('100000000000000000001');
var_dump(zend_test_int_cmp($a, $b));
var_dump(zend_test_int_cmp($b, $a));
var_dump(zend_test_int_cmp($a, $a));
var_dump(zend_test_int_cmp_long($a, 5));
var_dump(zend_test_int_sign(zend_test_int_neg($a)));
var_dump(zend_test_int_is_odd($a));
var_dump(zend_test_int_is_odd($b));
var_dump(zend_test_int_bit_length($a));
var_dump(zend_test_int_to_double($a));
var_dump(zend_test_int_from_double(3.9));
var_dump(zend_test_int_is_boxed(zend_test_int_from_double(1.0e30)));
echo zend_test_bigint_to_string(zend_test_int_from_double(1.0e30)) . "\n";
var_dump(zend_test_int_cmp(7, 7));

try {
    zend_test_int_cmp('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_cmp(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_cmp_long('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_sign('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_is_odd('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_bit_length('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_to_double('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(-1)
int(1)
int(0)
int(1)
int(-1)
bool(false)
bool(true)
int(67)
float(1.0E+20)
int(3)
bool(true)
1000000000000000019884624838656
int(0)
TypeError: zend_test_int_cmp(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_cmp(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_cmp_long(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_sign(): Argument #1 ($value) must be an integer
TypeError: zend_test_int_is_odd(): Argument #1 ($value) must be an integer
TypeError: zend_test_int_bit_length(): Argument #1 ($value) must be an integer
TypeError: zend_test_int_to_double(): Argument #1 ($value) must be an integer

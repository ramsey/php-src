--TEST--
bigint value op: shift left grows to a box; shift right saturates
--EXTENSIONS--
zend_test
--FILE--
<?php
echo zend_test_bigint_to_string(zend_test_int_shift_left(1, 100)) . "\n";
var_dump(zend_test_int_shift_left(1, 3));
echo zend_test_bigint_to_string(zend_test_int_shift_left(-1, 100)) . "\n";
var_dump(zend_test_int_shift_left(-1, 3));

$big = zend_test_bigint_make('1267650600228229401496703205376'); // 2**100
$hugeCount = zend_test_bigint_make('1000000000000');
var_dump(zend_test_int_shift_right($big, 100));
var_dump(zend_test_int_shift_right($big, $hugeCount));
var_dump(zend_test_int_shift_right(zend_test_int_neg($big), $hugeCount));
var_dump(zend_test_int_shift_right(1, $hugeCount));
var_dump(zend_test_int_shift_right(-1, $hugeCount));

try {
    zend_test_int_shift_left(1, zend_test_bigint_make('5000000000000'));
} catch (ArithmeticError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    zend_test_int_shift_left('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_shift_left(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_shift_right('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_shift_right(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
1267650600228229401496703205376
int(8)
-1267650600228229401496703205376
int(-8)
int(1)
int(0)
int(-1)
int(0)
int(-1)
ArithmeticError: The libtommath bigint backend cannot shift left by more than %d bits
TypeError: zend_test_int_shift_left(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_shift_left(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_shift_right(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_shift_right(): Argument #2 ($b) must be an integer

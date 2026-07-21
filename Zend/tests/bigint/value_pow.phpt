--TEST--
bigint value op: exponentiation grows to a box; over-reach throws
--EXTENSIONS--
zend_test
--FILE--
<?php
echo zend_test_bigint_to_string(zend_test_int_pow(2, 100)) . "\n";
var_dump(zend_test_int_pow(2, 10));
var_dump(zend_test_int_pow(7, 0));

$big = zend_test_bigint_make('1267650600228229401496703205376'); // 2**100
echo zend_test_bigint_to_string(zend_test_int_pow($big, 2)) . "\n";
var_dump(zend_test_int_pow(-2, 3));
echo zend_test_bigint_to_string(zend_test_int_pow(-2, 65)) . "\n";

try {
    zend_test_int_pow(2, zend_test_bigint_make('5000000000000'));
} catch (ArithmeticError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    zend_test_int_pow('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_pow(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
1267650600228229401496703205376
int(1024)
int(1)
1606938044258990275541962092341162602522202993782792835301376
int(-8)
-36893488147419103232
ArithmeticError: The libtommath bigint backend cannot raise to an exponent greater than %d
TypeError: zend_test_int_pow(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_pow(): Argument #2 ($b) must be an integer

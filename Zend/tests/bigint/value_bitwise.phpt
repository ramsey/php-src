--TEST--
bigint value op: infinite two's-complement bitwise ops
--EXTENSIONS--
zend_test
--FILE--
<?php
$big = zend_test_bigint_make('340282366920938463463374607431768211456'); // 2**128
$mask = zend_test_bigint_make('340282366920938463463374607431768211455'); // 2**128 - 1

echo zend_test_bigint_to_string(zend_test_int_or($big, $mask)) . "\n";
var_dump(zend_test_int_and($big, $mask));
echo zend_test_bigint_to_string(zend_test_int_xor($big, $mask)) . "\n";
var_dump(zend_test_int_not(0));
var_dump(zend_test_int_not(-1));
echo zend_test_bigint_to_string(zend_test_int_not($mask)) . "\n";

$negBig = zend_test_int_neg($big);
var_dump(zend_test_int_and($negBig, $mask));
var_dump(zend_test_int_or($negBig, $mask));
echo zend_test_bigint_to_string(zend_test_int_xor($negBig, $big)) . "\n";

var_dump(zend_test_int_and(12, 10));
var_dump(zend_test_int_or(12, 10));
var_dump(zend_test_int_xor(12, 10));

try {
    zend_test_int_and('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_and(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_or('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_or(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_xor('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_xor(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_not('x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
680564733841876926926749214863536422911
int(0)
680564733841876926926749214863536422911
int(-1)
int(0)
-340282366920938463463374607431768211456
int(0)
int(-1)
-680564733841876926926749214863536422912
int(8)
int(14)
int(6)
TypeError: zend_test_int_and(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_and(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_or(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_or(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_xor(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_xor(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_not(): Argument #1 ($value) must be an integer

--TEST--
bigint value op: truncated division and modulo, sign of the dividend
--EXTENSIONS--
zend_test
--FILE--
<?php
$n = zend_test_bigint_make('1000000000000000000000000000000');
$d = zend_test_bigint_make('7');
var_dump(zend_test_int_is_boxed(zend_test_int_div_trunc($n, $d)));
echo zend_test_bigint_to_string(zend_test_int_div_trunc($n, $d)) . "\n";
var_dump(zend_test_int_mod($n, $d));

$negN = zend_test_int_neg($n);
var_dump(zend_test_int_mod($negN, $d));

$min = zend_test_bigint_make((string) PHP_INT_MIN);
var_dump(zend_test_int_is_boxed(zend_test_int_div_trunc($min, -1)));

var_dump(zend_test_int_div_trunc(17, 5));
var_dump(zend_test_int_mod(17, 5));
var_dump(zend_test_int_mod(-17, 5));

var_dump(zend_test_int_mod_slow(17, 5));
var_dump(zend_test_int_mod_slow(-17, 5));
var_dump(zend_test_int_mod_slow(PHP_INT_MIN, -1));

try {
    zend_test_int_div_trunc('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_div_trunc(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_mod('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_mod(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
142857142857142857142857142857
int(1)
int(-1)
bool(true)
int(3)
int(2)
int(-2)
int(2)
int(-2)
int(0)
TypeError: zend_test_int_div_trunc(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_div_trunc(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_mod(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_mod(): Argument #2 ($b) must be an integer

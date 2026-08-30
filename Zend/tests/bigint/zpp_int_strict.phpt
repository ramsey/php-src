--TEST--
bigint: Z_PARAM_INT under strict_types accepts logical ints only
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);
var_dump(zend_test_zpp_int(5));
var_dump(zend_test_zpp_int(9223372036854775808));
var_dump(zend_test_zpp_int(zend_test_bigint_make('340282366920938463463374607431768211456')));
try {
    zend_test_zpp_int('5');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_zpp_int(5.0);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
var_dump(zend_test_zpp_int_clamp(9223372036854775808) === PHP_INT_MAX);
var_dump(zend_test_zpp_int_clamp(-9223372036854775809) === PHP_INT_MIN);
try {
    zend_test_zpp_int_clamp('5');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(5)
int(9223372036854775808)
int(340282366920938463463374607431768211456)
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, string given
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, float given
bool(true)
bool(true)
TypeError: zend_test_zpp_int_clamp(): Argument #1 ($n) must be of type int, string given

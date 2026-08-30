--TEST--
bigint: Z_PARAM_INT accepts logical ints and coerces weak scalars exactly
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_zpp_int(5));
var_dump(zend_test_zpp_int(9223372036854775808));
var_dump(zend_test_zpp_int(zend_test_bigint_make('340282366920938463463374607431768211456')));
var_dump(zend_test_zpp_int(true));
var_dump(zend_test_zpp_int(1e100) == 1e100);
var_dump(zend_test_zpp_int(5.5));
var_dump(zend_test_zpp_int('5'));
var_dump(zend_test_zpp_int('9223372036854775808'));
var_dump(zend_test_zpp_int(null));
try {
    zend_test_zpp_int('abc');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_zpp_int(NAN);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
int(5)
int(9223372036854775808)
int(340282366920938463463374607431768211456)
int(1)
bool(true)

Deprecated: Implicit conversion from float 5.5 to int loses precision in %s on line %d
int(5)
int(5)
int(9223372036854775808)

Deprecated: zend_test_zpp_int(): Passing null to parameter #1 ($i) of type int is deprecated in %s on line %d
int(0)
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, string given
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, float given

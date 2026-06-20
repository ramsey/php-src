--TEST--
Bigint: Z_PARAM_INT accepts IS_LONG and IS_BIGINT at full precision (weak mode)
--EXTENSIONS--
zend_test
--FILE--
<?php
// long passes through unchanged
var_dump(zend_test_zpp_int(5));

// Large bigint passes at full precision
$big = 2 ** 100;
$result = zend_test_zpp_int($big);
var_dump(get_debug_type($result));
var_dump(gettype($result));
var_dump($result === $big);

// In-range bigint (via zend_test_make_bigint) stays int
var_dump(zend_test_zpp_int(zend_test_make_bigint('7')));

// Numeric string coerces to int in weak mode
var_dump(zend_test_zpp_int('5'));

// Integral float coerces to int in weak mode
var_dump(zend_test_zpp_int(5.0));

// bool coerces to int
var_dump(zend_test_zpp_int(true));
var_dump(zend_test_zpp_int(false));

// Non-integral float emits deprecation warning and coerces to int via long-weak
var_dump(zend_test_zpp_int(5.5));

// Non-numeric string results in a TypeError
try {
    zend_test_zpp_int('abc');
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Out-of-range numeric string coerces to a bigint in weak mode (full precision)
var_dump(zend_test_zpp_int('18446744073709551616') === 2 ** 64);

// Out-of-range float results in a TypeError (float-to-bigint promotion deferred, like strings)
try {
    zend_test_zpp_int(1e30);
} catch (TypeError $e) {
    echo get_class($e) . ": " . $e->getMessage() . "\n";
}

// null emits a deprecation warning and coerces to int for Z_PARAM_INT (null coercion via long-weak)
var_dump(zend_test_zpp_int(null));

// null stays NULL for Z_PARAM_INT_OR_NULL
var_dump(zend_test_zpp_int_or_null(null));

// long passes OR_NULL variant
var_dump(zend_test_zpp_int_or_null(5));

// bigint passes OR_NULL variant at full precision
$result2 = zend_test_zpp_int_or_null($big);
var_dump($result2 === $big);
?>
--EXPECTF--
int(5)
string(3) "int"
string(7) "integer"
bool(true)
int(7)
int(5)
int(5)
int(1)
int(0)

Deprecated: Implicit conversion from float 5.5 to int loses precision in %s on line %d
int(5)
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, string given
bool(true)
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, float given

Deprecated: zend_test_zpp_int(): Passing null to parameter #1 ($i) of type int is deprecated in %s on line %d
int(0)
NULL
int(5)
bool(true)

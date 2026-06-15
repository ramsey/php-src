--TEST--
Bigint: Z_FLF_PARAM_INT accepts IS_LONG and IS_BIGINT at full precision via the frameless path (weak mode)
--EXTENSIONS--
zend_test
--FILE--
<?php
// zend_test_flf_int() has a @frameless-function {"arity": 1} annotation; calling it
// with a single positional argument exercises the ZEND_FRAMELESS_FUNCTION(zend_test_flf_int, 1)
// opcode path, which uses Z_FLF_PARAM_INT and potentially calls zend_flf_parse_arg_int_slow.

// long passes through unchanged
var_dump(zend_test_flf_int(5));

// Large bigint passes at full precision
$big = 2 ** 100;
$result = zend_test_flf_int($big);
var_dump(get_debug_type($result));
var_dump(gettype($result));
var_dump($result === $big);

// In-range bigint (via zend_test_make_bigint) stays int
var_dump(zend_test_flf_int(zend_test_make_bigint('7')));

// Numeric string coerces to int in weak mode
var_dump(zend_test_flf_int('5'));

// Integral float coerces to int in weak mode
var_dump(zend_test_flf_int(5.0));

// bool coerces to int
var_dump(zend_test_flf_int(true));
var_dump(zend_test_flf_int(false));

// Non-integral float emits deprecation warning and coerces to int via long-weak
var_dump(zend_test_flf_int(5.5));

// Non-numeric string results in a TypeError
try {
    zend_test_flf_int('abc');
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Out-of-range numeric string results in a TypeError (string-to-bigint promotion deferred)
try {
    zend_test_flf_int('18446744073709551616');
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// null emits a deprecation warning and coerces to int (null coercion via long-weak)
var_dump(zend_test_flf_int(null));

// null stays NULL for Z_FLF_PARAM_INT_OR_NULL
var_dump(zend_test_flf_int_or_null(null));

// long passes OR_NULL variant
var_dump(zend_test_flf_int_or_null(5));

// bigint passes OR_NULL variant at full precision
var_dump(zend_test_flf_int_or_null($big) === $big);

// numeric string coerces to int for OR_NULL variant in weak mode
var_dump(zend_test_flf_int_or_null('5'));

// Frameless operands are live variables; weak coercion must not mutate the caller's value
$s = '5';
var_dump(zend_test_flf_int($s));
var_dump($s);
$f = 5.0;
var_dump(zend_test_flf_int($f));
var_dump($f);
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
TypeError: zend_test_flf_int(): Argument #1 ($i) must be of type int, string given
TypeError: zend_test_flf_int(): Argument #1 ($i) must be of type int, string given

Deprecated: zend_test_flf_int(): Passing null to parameter #1 ($i) of type int is deprecated in %s on line %d
int(0)
NULL
int(5)
bool(true)
int(5)
int(5)
string(1) "5"
int(5)
float(5)

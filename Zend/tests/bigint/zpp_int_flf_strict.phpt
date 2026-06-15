--TEST--
Bigint: Z_FLF_PARAM_INT in strict mode accepts IS_LONG and IS_BIGINT, rejects others (frameless path)
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);

// zend_test_flf_int() is frameless (arity 1); the strict-types check goes through
// zend_flf_parse_arg_int_slow and flows into ZEND_FLF_ARG_USES_STRICT_TYPES.

// long passes in strict mode
var_dump(zend_test_flf_int(5));

// bigint passes at full precision in strict mode
$big = 2 ** 100;
var_dump(zend_test_flf_int($big) === $big);

// In-range bigint (via zend_test_make_bigint) stays int in strict mode
var_dump(zend_test_flf_int(zend_test_make_bigint('7')));

// string (even numeric) results in a TypeError in strict mode
try {
    zend_test_flf_int('5');
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Integral float results in a TypeError in strict mode
try {
    zend_test_flf_int(5.0);
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// null stays NULL for the OR_NULL variant; bigint passes
var_dump(zend_test_flf_int_or_null(null));
var_dump(zend_test_flf_int_or_null($big) === $big);
?>
--EXPECT--
int(5)
bool(true)
int(7)
TypeError: zend_test_flf_int(): Argument #1 ($i) must be of type int, string given
TypeError: zend_test_flf_int(): Argument #1 ($i) must be of type int, float given
NULL
bool(true)

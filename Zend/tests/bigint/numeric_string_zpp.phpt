--TEST--
Bigint: int and int|float parameters accept an out-of-range numeric string
--EXTENSIONS--
zend_test
--FILE--
<?php
// Internal Z_PARAM_INT: an out-of-range integer string is accepted as a bigint.
var_dump(zend_test_zpp_int('9223372036854775808') === 2 ** 63);
var_dump(zend_test_zpp_int('-9223372036854775809') === -(2 ** 63) - 1);
var_dump(zend_test_zpp_int('42'));

// Internal Z_PARAM_INT_OR_FLOAT: out-of-range integer string to bigint; float string becomes float.
var_dump(zend_test_zpp_int_or_float('9223372036854775808') === 2 ** 63);
var_dump(zend_test_zpp_int_or_float('1.5'));

// Old-style "i" logical int.
var_dump(zend_test_zpp_int_oldstyle('9223372036854775808') === 2 ** 63);

// Userland typed int parameter (weak mode).
function takesInt(int $x) {
    return $x;
}
var_dump(takesInt('9223372036854775808') === 2 ** 63);

// Userland typed int|float parameter (weak mode).
function takesIntOrFloat(int|float $x) {
    return $x;
}
var_dump(takesIntOrFloat('9223372036854775808') === 2 ** 63);
var_dump(takesIntOrFloat('1.5'));

// The digit limit applies when coercing to an int parameter.
ini_set('zend.int_string_max_digits', 640);
try {
    zend_test_zpp_int(str_repeat('9', 700));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
int(42)
bool(true)
float(1.5)
bool(true)
bool(true)
bool(true)
float(1.5)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

--TEST--
Bigint: zend_string_to_number classifies an out-of-range integer string as a bigint
--EXTENSIONS--
zend_test
--FILE--
<?php
// In-range integer string stays a long.
var_dump(zend_test_string_to_number('42'));

// Out-of-range integer strings become bigints.
var_dump(zend_test_string_to_number('9223372036854775808'));
var_dump(zend_test_string_to_number('9223372036854775808') === 2 ** 63);
var_dump(zend_test_string_to_number('-9223372036854775809'));

// Genuine floats stay floats.
var_dump(zend_test_string_to_number('1.5'));
var_dump(zend_test_string_to_number('1e308'));

// Non-numeric strings are rejected.
var_dump(zend_test_string_to_number('abc'));

// When there's trailing junk, zend_test_string_to_number returns false without allow_errors.
var_dump(zend_test_string_to_number('9223372036854775808abc'));

// With allow_errors, zend_test_string_to_number returns the leading number (without trailing junk).
var_dump(zend_test_string_to_number('9223372036854775808abc', true) === 2 ** 63);

// The digit limit applies on this conversion path.
ini_set('zend.int_string_max_digits', 640);
try {
    zend_test_string_to_number('1' . str_repeat('0', 700));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
int(42)
int(9223372036854775808)
bool(true)
int(-9223372036854775809)
float(1.5)
float(1.0E+308)
bool(false)
bool(false)
bool(true)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

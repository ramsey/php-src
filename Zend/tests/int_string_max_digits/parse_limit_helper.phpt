--TEST--
zend_check_int_string_digit_limit() throws when a decimal string exceeds the limit
--EXTENSIONS--
zend_test
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
// Exactly at the limit: ok.
var_dump(zend_test_check_int_string_digits(str_repeat('9', 640)));

// Sign is not counted as a digit, so 640 digits with a sign is still ok.
var_dump(zend_test_check_int_string_digits('-' . str_repeat('9', 640)));
var_dump(zend_test_check_int_string_digits('+' . str_repeat('9', 640)));

// One digit over the limit: ValueError.
try {
    zend_test_check_int_string_digits(str_repeat('9', 641));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// The limit of 0 disables the check.
ini_set('zend.int_string_max_digits', '0');
var_dump(zend_test_check_int_string_digits(str_repeat('9', 100000)));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
bool(true)

--TEST--
Bigint: arithmetic operators promote an out-of-range numeric string operand
--FILE--
<?php
// An out-of-range integer string operand promotes to a bigint, not a float.
var_dump('9223372036854775808' + 0 === 2 ** 63);
var_dump('9223372036854775808' + 0);
var_dump('9999999999999999999' * 2);

// Subtraction round-trips a bigint through its decimal string.
$s = (string) (2 ** 100);
var_dump($s - 1 === 2 ** 100 - 1);

// A float operand still dominates: the result stays a float.
var_dump(is_float('9223372036854775808' + 1.5));

// Arithmetic on an existing bigint never trips the digit limit.
var_dump(is_int((2 ** 100) + 1));

// A leading-numeric string warns and uses its (out-of-range) integer part.
var_dump('9223372036854775808abc' + 1 === 2 ** 63 + 1);

// The digit limit applies to the string operand's conversion.
ini_set('zend.int_string_max_digits', 640);
try {
    $x = str_repeat('9', 700) + 0;
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECTF--
bool(true)
int(9223372036854775808)
int(19999999999999999998)
bool(true)
bool(true)
bool(true)

Warning: A non-numeric value encountered in %s on line %d
bool(true)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

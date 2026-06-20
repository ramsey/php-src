--TEST--
Bigint: ++ and -- on an out-of-range numeric string yield a bigint
--FILE--
<?php
// ++ at the long boundary promotes to a bigint.
$s = '9223372036854775807';
$s++;
var_dump(is_int($s));
var_dump($s === 2 ** 63);

// ++ on an already-out-of-range integer string stays an int (bigint).
$s = '9223372036854775808';
$s++;
var_dump($s === 2 ** 63 + 1);

// -- at the long boundary promotes to a bigint.
$s = '-9223372036854775808';
$s--;
var_dump(is_int($s));
var_dump($s === -(2 ** 63) - 1);

// -- on an already-out-of-range integer string stays an int (bigint).
$s = '-9223372036854775809';
$s--;
var_dump($s === -(2 ** 63) - 2);

// Perl-style increment on a non-numeric string is unaffected.
$s = 'Az';
$s++;
var_dump($s);

// A float string still increments as a float.
$s = '1.5';
$s++;
var_dump($s);

// The digit limit applies when ++ converts the string.
ini_set('zend.int_string_max_digits', 640);
try {
    $s = str_repeat('9', 700);
    $s++;
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d
string(2) "Ba"
float(2.5)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

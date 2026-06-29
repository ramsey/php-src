--TEST--
FILTER_VALIDATE_INT: an out-of-int64 value validates to an exact bigint
--EXTENSIONS--
filter
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die('skip for 64-bit platforms'); ?>
--FILE--
<?php
$O = ['flags' => FILTER_FLAG_ALLOW_OCTAL];
$H = ['flags' => FILTER_FLAG_ALLOW_HEX];

// Decimal overflow keeps the exact value as a bigint instead of returning false.
var_dump(filter_var('9223372036854775808', FILTER_VALIDATE_INT));
var_dump(filter_var('-99999999999999999999999', FILTER_VALIDATE_INT));

// PHP_INT_MIN stays a long (a bigint demotes back to a long when it fits).
var_dump(filter_var('-9223372036854775808', FILTER_VALIDATE_INT) === PHP_INT_MIN);

// Octal/hex overflow produces a bigint instead of wrapping to a negative long.
var_dump(filter_var('01777777777777777777777', FILTER_VALIDATE_INT, $O));
var_dump(filter_var('-01000000000000000000000', FILTER_VALIDATE_INT, $O) === PHP_INT_MIN);
var_dump(filter_var('0xffffffffffffffff', FILTER_VALIDATE_INT, $H));

// Range bounds compare by value, so a bigint bound works.
$r = ['options' => ['min_range' => 0, 'max_range' => 100000000000000000000]];
var_dump(filter_var('99999999999999999999', FILTER_VALIDATE_INT, $r));
var_dump(filter_var('999999999999999999999', FILTER_VALIDATE_INT, $r));

// Over zend.int_string_max_digits stays rejected (no O(n^2) build on untrusted input).
ini_set('zend.int_string_max_digits', '640');
var_dump(filter_var(str_repeat('9', 641), FILTER_VALIDATE_INT));
?>
--EXPECT--
int(9223372036854775808)
int(-99999999999999999999999)
bool(true)
int(18446744073709551615)
bool(true)
int(18446744073709551615)
int(99999999999999999999)
bool(false)
bool(false)

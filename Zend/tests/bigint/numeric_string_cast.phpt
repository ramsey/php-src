--TEST--
Bigint: (int)/intval()/settype() of an out-of-range numeric string yield a bigint
--FILE--
<?php
// Compile-time constant expressions (a global const and a class const) also fold to a bigint.
const C_INT = (int) '9223372036854775808';
class CastBox {
    const X = (int) '9223372036854775808';
}

// Runtime (int) cast of a variable.
$s = '9223372036854775808';
var_dump((int) $s === 2 ** 63);
var_dump((int) $s);

// Runtime (int) cast of a literal.
var_dump((int) '9223372036854775808' === 2 ** 63);

// Negative out-of-range.
var_dump((int) '-9223372036854775809' === -(2 ** 63) - 1);

// Compile-time folded casts.
var_dump(C_INT === 2 ** 63);
var_dump(CastBox::X === 2 ** 63);

// intval(), default and explicit base 10.
var_dump(intval('9223372036854775808') === 2 ** 63);
var_dump(intval('9223372036854775808', 10) === 2 ** 63);

// settype() to int.
$x = '9223372036854775808';
settype($x, 'int');
var_dump(is_int($x));
var_dump($x === 2 ** 63);

// In-range and float strings are unchanged: (int) truncates a float string.
var_dump((int) '42');
var_dump((int) '1.9');

// Non-numeric string casts to 0.
var_dump((int) 'abc');

// The digit limit applies to the conversion.
ini_set('zend.int_string_max_digits', 640);
try {
    $big = (int) str_repeat('9', 700);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
int(9223372036854775808)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
int(42)
int(1)
int(0)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

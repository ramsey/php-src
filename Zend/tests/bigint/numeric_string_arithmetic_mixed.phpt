--TEST--
Bigint: a coerced numeric-string bigint mixes correctly with floats, division, and pow
--FILE--
<?php
// A bigint operand paired with a float yields a float (the float dominates).
var_dump(is_float('9223372036854775808' - 0.5));
var_dump(is_float('9223372036854775808' * 2.0));
var_dump(is_float('99999999999999999999' / 2.0));

// Exact integer division of an out-of-range string stays an int (bigint).
$q = '99999999999999999999' / 3;
var_dump(is_int($q));
var_dump($q);

// Inexact division yields a float.
var_dump(is_float('99999999999999999999' / 7));

// pow: a bigint base with an integer exponent stays an int (bigint).
var_dump('99999999999999999999' ** 2);

// pow: a bigint base with a float exponent yields a float (the float dominates).
var_dump(is_float('99999999999999999999' ** 1.5));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
int(33333333333333333333)
bool(true)
int(9999999999999999999800000000000000000001)
bool(true)

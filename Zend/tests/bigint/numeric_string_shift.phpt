--TEST--
Bigint: << >> promote an out-of-range numeric-string shiftee
--INI--
opcache.enable_cli=0
--FILE--
<?php
$s = (string) (PHP_INT_MAX + 1); // out-of-range integer string
$b = PHP_INT_MAX + 1;            // the same value as a bigint

// An out-of-range string shiftee promotes to a bigint and shifts at full precision.
var_dump(($s << 1) === ($b << 1));
var_dump(($s >> 3) === ($b >> 3));
var_dump(is_int($s << 1));
var_dump(is_int($s >> 3));

// An in-range string shiftee that overflows on the shift becomes a bigint.
var_dump(('1' << 70) === (2 ** 70));

// ...and a negative count from a numeric string still errors.
try {
    $r = $s << '-1';
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

// A huge >> count saturates to the sign of the shiftee (0 or -1), not LONG_MAX.
var_dump(1 >> $s);
var_dump(-1 >> $s);
var_dump($s >> $s);

// Compound assignment on a numeric-string variable promotes in place.
$x = $s;
$x <<= 1;
var_dump($x === ($b << 1));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
Bit shift by negative number
int(0)
int(-1)
int(0)
bool(true)

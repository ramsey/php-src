--TEST--
Bigint: modulo promotes an out-of-range numeric-string operand to a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php
$s = (string) (PHP_INT_MAX + 1); // smallest out-of-range positive integer, as a string
$b = PHP_INT_MAX + 1;            // the same value as a bigint

// PHP_INT_MAX + 1 is even, so the remainder is 0
var_dump($s % 2);
var_dump($s % 2 === $b % 2);
var_dump($s % 10 === $b % 10);

// Both operands promote.
var_dump($s % $s === 0);

// A small dividend modulo a large divisor is the dividend itself.
var_dump(7 % $s === 7);
var_dump(-7 % $s === -7);

// The result is always an integer.
var_dump(is_int($s % 3));

// Compound assignment on a numeric-string variable promotes in place.
$c = $s;
$c %= 2;
var_dump($c);

// Modulo by zero still throws even when the dividend promotes.
try {
    $r = $s % 0;
} catch (DivisionByZeroError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
int(0)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
int(0)
Modulo by zero

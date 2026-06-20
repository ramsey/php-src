--TEST--
Bigint: & | ^ promote an out-of-range numeric-string operand
--INI--
opcache.enable_cli=0
--FILE--
<?php
$s = (string) (PHP_INT_MAX + 1); // out-of-range integer string
$b = PHP_INT_MAX + 1;            // the same value as a bigint

// One operand is a number: the string promotes to a bigint and the bitwise op is
// integer (not byte-wise). PHP_INT_MAX + 1 is even, so & 1 is 0.
var_dump($s & 1);
var_dump(($s & 1) === ($b & 1));
var_dump(($s | 0) === $b);
var_dump(($s ^ 0) === $b);
var_dump((1 & $s) === (1 & $b));
var_dump(is_int($s | 0));

// Both operands strings: byte-wise string bitwise, unchanged (NOT a bigint).
var_dump('AB' | 'C');
var_dump('AB' & 'C');
var_dump(bin2hex('AB' ^ 'C'));
var_dump(is_string($s & $s));
var_dump(strlen($s & $s) === strlen($s));

// Compound assignment on a numeric-string variable promotes in place.
$x = $s;
$x &= 1;
var_dump($x);
?>
--EXPECT--
int(0)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
string(2) "CB"
string(1) "A"
string(2) "02"
bool(true)
bool(true)
int(0)

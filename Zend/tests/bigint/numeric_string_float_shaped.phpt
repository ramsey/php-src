--TEST--
bigint: float-shaped numeric strings convert by nearest-double value
--DESCRIPTION--
An integer string too large for a native integer converts to an exact integer.
A numeric string carrying a decimal point or an exponent is float-shaped, and
it converts to the nearest float no matter how large its integer part is.

The shape of the string decides this, not its magnitude. Every arithmetic
operator takes the same route, so '99999999999999999999.5' reaches 1.0E+20
through addition, subtraction, multiplication, and division alike. Incrementing
that string leaves it at 1.0E+20, since 1 falls below the distance between
neighboring floats that large. Comparison agrees with arithmetic, so the
string compares equal to 1.0E20 even though the two differ by 0.5 as written.
--FILE--
<?php
$s = '99999999999999999999.5';

var_dump($s + 0);
var_dump($s - 0);
var_dump($s * 1);
var_dump($s / 1);

$x = $s;
$x++;
var_dump($x);

$y = $s;
$y--;
var_dump($y);

var_dump('-99999999999999999999.5' + 0);
var_dump('99999999999999999999e2' + 0);

// Arithmetic and comparison agree on the string's value.
var_dump($s == 1.0E20);
var_dump($s + 0 == 1.0E20);
?>
--EXPECT--
float(1.0E+20)
float(1.0E+20)
float(1.0E+20)
float(1.0E+20)
float(1.0E+20)
float(1.0E+20)
float(-1.0E+20)
float(1.0E+22)
bool(true)
bool(true)

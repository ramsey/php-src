--TEST--
Bigint: modulo keeps a bigint operand's full value (truncated, sign of dividend)
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big = PHP_INT_MAX + 1;
$sq  = $big * $big;

// bigint % long (result always fits a long):
var_dump($big % 10);
var_dump((-$big * 3) % 7);     // negative dividend produces negative remainder (truncated)
var_dump($big % -10);          // remainder takes the sign of the dividend, not divisor

// long % bigint: |long| < |bigint|, so the remainder is the dividend itself:
var_dump(7 % $big);
var_dump(-7 % $big);
var_dump(0 % $big);

// bigint % bigint:
var_dump($sq % ($big + 5));
var_dump(($sq + $big) % $sq);  // remainder exceeds the long range and stays a bigint
var_dump(($sq + 25) % $sq);    // remainder fits a long

// non-integer operands coerce to int (as % always has):
var_dump($big % 1000);
var_dump($big % "1000");

// the result is always an integer:
var_dump(is_int(($sq + $big) % $sq));

// modulo by zero still throws:
try {
    $r = $big % 0;
} catch (DivisionByZeroError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECT--
int(8)
int(-3)
int(8)
int(7)
int(-7)
int(0)
int(25)
int(9223372036854775808)
int(25)
int(808)
int(808)
bool(true)
Modulo by zero

--TEST--
GMP accepts an out-of-int64 (bigint) operand losslessly
--EXTENSIONS--
gmp
--FILE--
<?php

$big = 2 ** 100;

// As a function operand.
var_dump(gmp_strval(gmp_abs(-$big)));
var_dump(gmp_strval(gmp_add($big, 1)));
var_dump(gmp_strval(gmp_mul($big, $big)));

// Mixed with a GMP object via operator overloading.
$g = gmp_init(10);
var_dump(gmp_strval($g + $big));
var_dump(gmp_strval($big * $g));
?>
--EXPECT--
string(31) "1267650600228229401496703205376"
string(31) "1267650600228229401496703205377"
string(61) "1606938044258990275541962092341162602522202993782792835301376"
string(31) "1267650600228229401496703205386"
string(32) "12676506002282294014967032053760"

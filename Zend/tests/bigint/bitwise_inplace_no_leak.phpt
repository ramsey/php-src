--TEST--
Bigint: in-place bitwise ops do not leak the result-aliased operand
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big64 = (9223372036854775807 + 1) * 8;     // bigint 2^66 (64-bit)
$big32 = (2147483647 + 1) * 8;              // bigint 2^34 (32-bit)

// $x <<= n : result aliases the bigint operand, stays a bigint.
$x = 9223372036854775807 + 1;
$x <<= 4;
var_dump($x);

$x = 2147483647 + 1;
$x <<= 4;
var_dump($x);

// $y >>= n : result aliases the bigint operand, demotes to a long.
$y = $big64;
$y >>= 4;
var_dump($y);

$y = $big32;
$y >>= 4;
var_dump($y);

// $z = ~$z : unary, result aliases the operand.
$z = 9223372036854775807 + 1;
$z = ~$z;
var_dump($z);

$z = 2147483647 + 1;
$z = ~$z;
var_dump($z);

// $a &= n : result aliases, demotes.
$a = $big64 + 0xFF;
$a &= 0xFF;
var_dump($a);

$a = $big32 + 0xFF;
$a &= 0xFF;
var_dump($a);

// $b |= n : result aliases, stays a bigint.
$b = $big64;
$b |= 0xFF;
var_dump($b);

$b = $big32;
$b |= 0xFF;
var_dump($b);

// $c ^= bigint : result aliases, demotes to 0.
$c = $big64;
$c ^= $big64;
var_dump($c);

$c = $big32;
$c ^= $big32;
var_dump($c);
?>
--EXPECT--
int(147573952589676412928)
int(34359738368)
int(4611686018427387904)
int(1073741824)
int(-9223372036854775809)
int(-2147483649)
int(255)
int(255)
int(73786976294838206719)
int(17179869439)
int(0)
int(0)

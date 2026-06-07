--TEST--
Bigint: in-place addition does not leak the result-aliased operand
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// result aliases op1: bigint += long
$x = PHP_INT_MAX;
$x += 1;               // long to bigint (op1 is long here, no alias leak)
$x += 1;               // bigint += long, result aliases the bigint operand
$x += 1;
var_dump($x);

// result aliases op1 and op2: bigint += bigint
$y = PHP_INT_MAX + 1;  // bigint
$y += $y;              // bigint += bigint, result aliases an operand
var_dump($y);

// internal accumulator path (array_sum) over an overflowing run
var_dump(array_sum([PHP_INT_MAX, 1, 4096]));
?>
--EXPECT--
int(2147483650)
int(4294967296)
int(2147487744)

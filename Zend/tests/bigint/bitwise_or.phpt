--TEST--
Bigint: | operates in two's complement on full-precision operands
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big64 = (9223372036854775807 + 1) * 8;     // bigint 2^66 (64-bit)
$big32 = (2147483647 + 1) * 8;              // bigint 2^34 (32-bit)

// bigint | long: sets low bits, result stays a bigint.
var_dump($big64 | 0xFF);
var_dump($big32 | 0xFF);

// x | 0 === x.
var_dump(($big64 | 0) === $big64);
var_dump(($big32 | 0) === $big32);

// bigint | bigint.
var_dump(($big64 | ($big64 + 1)) === ($big64 + 1));
var_dump(($big32 | ($big32 + 1)) === ($big32 + 1));

// -1 is all-ones, so x | -1 === -1.
var_dump($big64 | -1);
var_dump($big32 | -1);
?>
--EXPECT--
int(73786976294838206719)
int(17179869439)
bool(true)
bool(true)
bool(true)
bool(true)
int(-1)
int(-1)

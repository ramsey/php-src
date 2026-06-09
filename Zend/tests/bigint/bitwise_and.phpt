--TEST--
Bigint: & operates in two's complement on full-precision operands
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big64 = (9223372036854775807 + 1) * 8;     // bigint 2^66 (64-bit)
$big64_2 = $big64 + 0xFF;                   // 2^66 + 255

$big32 = (2147483647 + 1) * 8;              // bigint 2^34 (32-bit)
$big32_2 = $big32 + 0xFF;                   // 2^34 + 255

// bigint & long: masking shrinks the result back to a plain int.
var_dump($big64 & 0xFF);
var_dump($big64_2 & 0xFF);
var_dump($big32 & 0xFF);
var_dump($big32_2 & 0xFF);

// -1 is all-ones in two's complement, so x & -1 === x.
var_dump(($big64 & -1) === $big64);
var_dump(($big32 & -1) === $big32);

// bigint & bigint.
var_dump(($big64_2 & $big64) === $big64);
var_dump(($big32_2 & $big32) === $big32);

// Negative bigint operand (two's complement).
$negbig64 = -($big64 + 1);
var_dump(($negbig64 & -1) === $negbig64);

$negbig32 = -($big32 + 1);
var_dump(($negbig32 & -1) === $negbig32);
?>
--EXPECT--
int(0)
int(255)
int(0)
int(255)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

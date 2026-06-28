--TEST--
Bigint: << overflowing the long range promotes to a bigint instead of wrapping
--INI--
opcache.enable_cli=0
--FILE--
<?php
// 1 << 70 used to wrap to 0; now it is 2^70.
var_dump(1 << 70);

// Small shift still produces a plain int.
var_dump(1 << 4);

// Shift that exactly overflows the long range (32-bit platform).
var_dump(1 << 31);

// Bigint operand shifted further (32-bit platform).
$big32 = 2147483647 + 1; // long (32-bit) + long promotes to bigint.
var_dump($big32 << 4);

// Shift that exactly overflows the long range (64-bit platform).
var_dump(1 << 63);

// Bigint operand shifted further (64-bit platform).
$big64 = 9223372036854775807 + 1; // long (64-bit) + long promotes to bigint.
var_dump($big64 << 4);

// 0 << anything non-negative is 0, whether it fits in a long or not.
var_dump(0 << 100);
var_dump(0 << (2 ** 70));                 // bigint count
$strCount = '1180591620717411303424';     // 2 ** 70 as a string
var_dump(0 << $strCount);                  // numeric-string bigint count

// Negative shift count throws.
try {
    $r = 1 << -1;
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Negative operand keeps two's-complement semantics, promoting in magnitude.
var_dump(-1 << 70);
?>
--EXPECT--
int(1180591620717411303424)
int(16)
int(2147483648)
int(34359738368)
int(9223372036854775808)
int(147573952589676412928)
int(0)
int(0)
int(0)
Bit shift by negative number
int(-1180591620717411303424)

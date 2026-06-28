--TEST--
Bigint: >> shifts a full-precision operand and saturates huge counts to 0/-1
--INI--
opcache.enable_cli=0
--FILE--
<?php
// 64-bit long max + 1 promotes to bigint, then multiply by 8 (i.e., 2^66).
$big64 = (9223372036854775807 + 1) * 8;

// 32-bit long max + 1 promotes to bigint, then multiply by 8 (i.e., 2^34).
$big32 = (2147483647 + 1) * 8;

// Shrinks back into the long range and demotes to a plain int.
var_dump($big64 >> 3);  // 2^63 == 9223372036854775808 (still bigint)
var_dump($big64 >> 4);  // 2^62 (fits a long on 64-bit platform)
var_dump($big32 >> 3);  // 2^31 == 2147483648 (still bigint on 32-bit platform)
var_dump($big32 >> 4);  // 2^30 (fits a long)

// Shifting a bigint past its top bit saturates: 0 for non-negative, -1 for negative.
var_dump($big64 >> 1000);
var_dump((-$big64) >> 1000);
var_dump($big32 >> 1000);
var_dump((-$big32) >> 1000);

// A long operand shifted right by a bigint count saturates the same way.
var_dump(255 >> ($big64));
var_dump((-255) >> ($big64));
var_dump(255 >> ($big32));
var_dump((-255) >> ($big32));

// A bigint operand shifted by a count beyond the backend's reach saturates, too.
var_dump($big64 >> 2147483648);
var_dump((-$big64) >> 2147483648);
var_dump($big64 >> $big64);
var_dump((-$big64) >> $big64);

// Negative shift count throws.
try {
    $r = $big64 >> -1;
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}
try {
    $r = $big32 >> -1;
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECT--
int(9223372036854775808)
int(4611686018427387904)
int(2147483648)
int(1073741824)
int(0)
int(-1)
int(0)
int(-1)
int(0)
int(-1)
int(0)
int(-1)
int(0)
int(-1)
int(0)
int(-1)
Bit shift by negative number
Bit shift by negative number

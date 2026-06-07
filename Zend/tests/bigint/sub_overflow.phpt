--TEST--
Bigint: integer subtraction promotes on overflow instead of becoming float
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$max = PHP_INT_MAX;
$min = PHP_INT_MIN;

// long - long that overflows the long range stays an integer:
var_dump($min - 1);
var_dump($max - $min);

$big = $min - 1;

// bigint - long that fits back in a long normalizes to a plain int:
var_dump($big - (-1));
var_dump(is_int($big));

// long - bigint:
var_dump(0 - $big);

// bigint - bigint that fits back in a long normalizes to a plain int:
var_dump($big - $big);
?>
--EXPECT--
int(-9223372036854775809)
int(18446744073709551615)
int(-9223372036854775808)
bool(true)
int(9223372036854775809)
int(0)

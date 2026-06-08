--TEST--
Bigint: integer exponentiation promotes on overflow instead of becoming float
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$max = PHP_INT_MAX;

// long ** long with a non-negative exponent that overflows the long range
// stays an exact integer instead of degrading to float:
var_dump(2 ** 64);
var_dump(10 ** 30);
var_dump($max ** 2);

// negative base keeps its sign through the exact integer result:
var_dump((-2) ** 65);

$big = $max + 1;

// bigint ** long (non-negative exponent):
var_dump($big ** 2);
var_dump($big ** 1);
var_dump($big ** 0);

// a result that stays within the long range remains a plain int:
var_dump(2 ** 10);

// an overflowing power is still reported as an integer to userland:
var_dump(is_int(2 ** 64));

// a negative exponent is fractional, so the result is a float:
var_dump(2 ** -1);
var_dump($big ** -1);
?>
--EXPECT--
int(18446744073709551616)
int(1000000000000000000000000000000)
int(4611686014132420609)
int(-36893488147419103232)
int(4611686018427387904)
int(2147483648)
int(1)
int(1024)
bool(true)
float(0.5)
float(4.656612873077393E-10)

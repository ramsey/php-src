--TEST--
Bigint: integer multiplication promotes on overflow instead of becoming float
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$max = PHP_INT_MAX;

// long * long that overflows the long range stays an integer:
var_dump($max * 2);
var_dump($max * $max);

$big = $max + 1;

// bigint * long and long * bigint:
var_dump($big * 2);
var_dump(2 * $big);

// bigint * bigint:
var_dump($big * $big);

// products that fit back in a long normalize to a plain int:
var_dump($big * 0);
var_dump(is_int($big * $big));
?>
--EXPECT--
int(18446744073709551614)
int(85070591730234615847396907784232501249)
int(18446744073709551616)
int(18446744073709551616)
int(85070591730234615865843651857942052864)
int(0)
bool(true)

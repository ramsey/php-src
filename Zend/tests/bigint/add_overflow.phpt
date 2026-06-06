--TEST--
Bigint: integer addition promotes on overflow instead of becoming float
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$max = PHP_INT_MAX;
var_dump($max + 1);
var_dump($max + $max);

$min = PHP_INT_MIN;
var_dump($min + (-1));

// result that fits back in a long must normalize to a plain int:
$big = $max + 1;          // bigint
var_dump($big + (-1));    // back to PHP_INT_MAX, as int
var_dump(is_int($big));
?>
--EXPECT--
int(9223372036854775808)
int(18446744073709551614)
int(-9223372036854775809)
int(9223372036854775807)
bool(true)

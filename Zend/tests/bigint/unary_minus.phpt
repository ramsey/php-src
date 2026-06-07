--TEST--
Bigint: unary minus/plus on a bigint
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big = PHP_INT_MAX + 1;

// -$big is exactly PHP_INT_MIN, which fits a long again:
var_dump(-$big);
var_dump(is_int(-$big));

$big2 = PHP_INT_MAX + 2;

// -$big2 stays a bigint:
var_dump(-$big2);

// unary plus leaves the bigint unchanged:
var_dump(+$big);
?>
--EXPECT--
int(-9223372036854775808)
bool(true)
int(-9223372036854775809)
int(9223372036854775808)

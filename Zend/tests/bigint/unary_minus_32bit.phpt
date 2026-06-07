--TEST--
Bigint: unary minus/plus on a bigint
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
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
int(-2147483648)
bool(true)
int(-2147483649)
int(2147483648)

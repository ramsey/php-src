--TEST--
Bigint: ++/-- promote to IS_BIGINT on overflow instead of becoming float
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// post/pre increment past PHP_INT_MAX:
$x = PHP_INT_MAX;
$x++;
var_dump($x);
var_dump(is_int($x));

$x = PHP_INT_MAX;
++$x;
var_dump($x);
var_dump(is_int($x));

// post/pre decrement past PHP_INT_MIN:
$y = PHP_INT_MIN;
$y--;
var_dump($y);
var_dump(is_int($y));

$y = PHP_INT_MIN;
--$y;
var_dump($y);
var_dump(is_int($y));

// decrementing a bigint back into long range normalizes to a plain int:
$b = PHP_INT_MAX + 1;
$b--;
var_dump($b);
var_dump(is_int($b));

// incrementing a bigint stays a bigint:
$c = PHP_INT_MAX + 2;
$c++;
var_dump($c);

// pre-inc/dec returning the new (bigint) value must keep refcounts sane:
$x = PHP_INT_MAX;
$r = ++$x;
var_dump($x, $r);

$y = PHP_INT_MIN;
$r = --$y;
var_dump($y, $r);
?>
--EXPECT--
int(9223372036854775808)
bool(true)
int(9223372036854775808)
bool(true)
int(-9223372036854775809)
bool(true)
int(-9223372036854775809)
bool(true)
int(9223372036854775807)
bool(true)
int(9223372036854775810)
int(9223372036854775808)
int(9223372036854775808)
int(-9223372036854775809)
int(-9223372036854775809)

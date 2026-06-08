--TEST--
Bigint: division with a float operand is always a float, even when exact
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big = PHP_INT_MAX + 1;
$sq  = $big * $big;

// bigint / float, mathematically exact, but a float operand forces a float:
var_dump(is_float($big / 2.0));
var_dump(is_float($sq / 4.0));

// float / bigint:
var_dump(is_float(2.0 / $big));

// bigint / float, inexact:
var_dump(is_float($big / 1.5));

// a bigint that came from float-typed arithmetic must not resurrect as int:
var_dump(is_float(($big + 0.0) / 2));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

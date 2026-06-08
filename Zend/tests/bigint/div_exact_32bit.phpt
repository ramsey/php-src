--TEST--
Bigint: exact division stays an integer, inexact division becomes a float
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big = PHP_INT_MAX + 1;
$sq  = $big * $big;

// bigint / long, exact, quotient fits a long:
var_dump($big / 2);

// bigint / long, inexact, quotient is a float:
var_dump(is_float($big / 3));

// bigint / bigint, exact, quotient is itself a bigint:
var_dump($sq / $big);

// bigint / bigint, exact, quotient fits a long:
var_dump($big / $big);

// bigint / bigint, exact, quotient stays a bigint:
var_dump($sq / 2);

// negative dividend, exact, demotes to PHP_INT_MIN:
var_dump((-$sq) / $big);

// long / bigint: only 0 is exact, everything else is a float:
var_dump(0 / $big);
var_dump(is_float(1 / $big));

// bigint / long, inexact, quotient is a float:
var_dump(is_float($big / PHP_INT_MAX));

// division by zero still throws:
try {
    $r = $big / 0;
} catch (DivisionByZeroError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECT--
int(1073741824)
bool(true)
int(2147483648)
int(1)
int(2305843009213693952)
int(-2147483648)
int(0)
bool(true)
bool(true)
Division by zero

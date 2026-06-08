--TEST--
Bigint: ~ produces a full-precision result instead of truncating to long
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$big = PHP_INT_MAX + 1;

// ~x == -x - 1, so ~big is below PHP_INT_MIN and stays a bigint.
var_dump(~$big);

// Round trip: ~~x === x.
var_dump(~~$big === $big);

// Negative bigint operand below PHP_INT_MIN.
$neg = -($big + 1);       // bigint -2147483649
var_dump(~$neg);          // -(-x) - 1 = x - 1 = 2147483648

// Cross-check identity against subtraction: ~x === -x - 1
var_dump(~$big === -$big - 1);
?>
--EXPECT--
int(-2147483649)
bool(true)
int(2147483648)
bool(true)

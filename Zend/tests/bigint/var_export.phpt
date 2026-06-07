--TEST--
IS_BIGINT: var_export emits a decimal literal
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--FILE--
<?php
$pos = PHP_INT_MAX + 1;
$neg = PHP_INT_MIN + -1;

// var_export emits a bare decimal literal for either sign.
echo var_export($pos, true), "\n";
echo var_export($neg, true), "\n";

// The positive literal round-trips back to the same bigint.
$rt1 = eval('return ' . var_export($pos, true) . ';');
var_dump($rt1 === $pos);

// The negative literal round-trips back to the same bigint.
$rt2 = eval('return ' . var_export($neg, true) . ';');
var_dump($rt2 === $neg);
?>
--EXPECT--
9223372036854775808
-9223372036854775809
bool(true)
bool(true)

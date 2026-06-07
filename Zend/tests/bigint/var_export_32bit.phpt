--TEST--
IS_BIGINT: var_export emits a decimal literal
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
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
2147483648
-2147483649
bool(true)
bool(true)

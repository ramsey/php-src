--TEST--
abs(): PHP_INT_MIN remains at full precision
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platforms only"); ?>
--FILE--
<?php

// Showcase: abs(PHP_INT_MIN) previously degraded to float; now full precision
var_dump(abs(PHP_INT_MIN) === 2 ** 63);
var_dump(abs(PHP_INT_MIN));

?>
--EXPECT--
bool(true)
int(9223372036854775808)

--TEST--
Bigint: base_convert() round-trips signed bigints
--FILE--
<?php
// Large value round-trips through a bigint (40 hex digits = 160 bits).
$hex = str_repeat('f', 40);
$bin = base_convert($hex, 16, 2);
var_dump($bin === str_repeat('1', 160));
var_dump(base_convert($bin, 2, 16) === $hex);

// Round-trip via decimal.
$dec = base_convert($hex, 16, 10);
var_dump(base_convert($dec, 10, 16) === $hex);

// Signed input and output.
var_dump(base_convert('-ff', 16, 2) === '-11111111');
var_dump(base_convert('-ff', 16, 10) === '-255');
var_dump(base_convert('-zz', 36, 10) === '-1295');
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

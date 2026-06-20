--TEST--
Bigint: comparison still does not promote an out-of-range numeric string (deferred)
--FILE--
<?php
// Comparison (==, <=>, ...) does not yet promote an out-of-range numeric string
// operand to a bigint; it still compares via the lossy silent double conversion.
// The integer operators (% & | ^ << >>) already promote. Comparison promotion is
// a separate follow-up; this pins the current behavior so that follow-up flips
// these assertions deliberately rather than silently.
$big = 2 ** 63;               // a bigint
$str = '9223372036854775809'; // 2**63 + 1 as a string (differs by exactly 1)

// Lossy: both collapse to the same double, so they compare equal even though the
// exact integers differ by one. Exact promotion would give bool(false) / int(-1).
var_dump($big == $str);
var_dump($big <=> $str);
?>
--EXPECT--
bool(true)
int(0)

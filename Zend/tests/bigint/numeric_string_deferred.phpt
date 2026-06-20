--TEST--
Bigint: bitwise operators and comparison still degrade an out-of-range numeric string (deferred)
--FILE--
<?php
// The bitwise operators (& | ^ << >>) and comparison do not yet promote an
// out-of-range numeric string operand to a bigint; they saturate it to a long
// (or compare without promotion). Promotion to bigint is deferred; the test pins
// the current behavior so the follow-up that adds bigint promotion flips these
// assertions deliberately rather than silently. (Modulo already promotes.)

$big = '9223372036854775808'; // 2**63: an even number, but it saturates to the odd LONG_MAX

// A promoted operand to & would give 0 (2**63 is even); the saturated LONG_MAX gives 1.
var_dump($big & 1);
var_dump($big | 0);

// Comparison neither throws nor promotes.
var_dump($big == $big);
?>
--EXPECT--
int(1)
int(9223372036854775807)
bool(true)

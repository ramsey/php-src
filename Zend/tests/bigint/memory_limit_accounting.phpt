--TEST--
Bigint: limb allocations count against memory_limit
--INI--
memory_limit=64M
--FILE--
<?php
// Shifting 1 left by two billion bits needs roughly 250 MB of limbs, far over
// the 64 MB limit, so the allocation must fail as a bounded PHP memory error
// rather than succeeding through an untracked libc malloc.
$bits = 2000000000;
$big = 1 << $bits;
var_dump($big);
?>
--EXPECTF--
Fatal error: Allowed memory size of %d bytes exhausted%s(tried to allocate %d bytes) in %s on line %d

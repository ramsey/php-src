--TEST--
Bigint: limb allocations count against memory_limit
--INI--
memory_limit=64M
--FILE--
<?php
// To exercise the allocator directly we build a ~25 MB bigint, then square it.
// The ~50 MB product pushes past the 64 MB limit. There's no up-front guard on
// multiplication hitting the memory limit, so this must fail as a bounded PHP
// memory error rather than succeeding through an untracked libc malloc.
$bits = 200000000;
$a = 1 << $bits;
$big = $a * $a;
var_dump($big);
?>
--EXPECTF--
Fatal error: Allowed memory size of %d bytes exhausted%s(tried to allocate %d bytes) in %s on line %d

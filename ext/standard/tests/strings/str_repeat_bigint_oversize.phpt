--TEST--
str_repeat() over-limit $times fails allocation
--INI--
memory_limit=64M
--FILE--
<?php
str_repeat('ab', 10 ** 30);
?>
--EXPECTF--
Fatal error: Possible integer overflow in memory allocation (2 * %d + 32) in %s on line %d

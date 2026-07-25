--TEST--
bigint: big integer literals in class constants, property defaults, and attribute arguments
--FILE--
<?php
require __DIR__ . '/literal_opcache_common.inc';

debug_zval_dump(Box::BIG);
debug_zval_dump(Box::FOLD);
?>
--EXPECTF--
340282366920938463463374607431768211456
9223372036854775808
340282366920938463463374607431768211456
340282366920938463463374607431768211456
bool(true)
bool(true)
int(340282366920938463463374607431768211456) bigint refcount(%d)
int(9223372036854775808) bigint refcount(%d)

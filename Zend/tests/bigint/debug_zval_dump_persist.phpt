--TEST--
bigint: debug_zval_dump reports refcount on a persisted big literal and a runtime box
--INI--
opcache.enable_cli=1
--FILE--
<?php
function add(int $a, int $b): int {
    return $a + $b;
}

$persisted = 123456789012345678901234567890;
$runtime = add(123456789012345678901234567890, 1);

debug_zval_dump($persisted);
debug_zval_dump($runtime);
?>
--EXPECT--
int(123456789012345678901234567890) bigint refcount(2)
int(123456789012345678901234567891) bigint refcount(2)

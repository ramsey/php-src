--TEST--
JIT INC: 021
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.file_update_protection=0
opcache.protect_memory=1
;opcache.jit_debug=257
--EXTENSIONS--
opcache
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only");
?>
--FILE--
<?php
function inc(int|float $x) {
    return ++$x;
}
function dec(int|float $x) {
    return --$x;
}
var_dump(inc(PHP_INT_MAX));
var_dump(inc(1.1));
var_dump(dec(PHP_INT_MIN));
var_dump(dec(1.1));
?>
--EXPECT--
int(9223372036854775808)
float(2.1)
int(-9223372036854775809)
float(0.10000000000000009)

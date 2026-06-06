--TEST--
Bigint: decimal integer literal overflowing ZEND_LONG becomes int, not float
--FILE--
<?php
var_dump(9223372036854775808);
var_dump(99999999999999999999999999999999);
var_dump(is_int(9223372036854775808));
var_dump(9223372036854775807); // PHP_INT_MAX (64-bit; stays int on 64-bit)
var_dump(2147483647);          // PHP_INT_MAX (32-bit; stays int on 32-bit and 64-bit)
?>
--EXPECT--
int(9223372036854775808)
int(99999999999999999999999999999999)
bool(true)
int(9223372036854775807)
int(2147483647)

--TEST--
Bigint: hexadecimal integer literal overflowing ZEND_LONG becomes int, not float
--FILE--
<?php
var_dump(0x8000000000000000);
var_dump(0xFFFFFFFFFFFFFFFF);
var_dump(0xaBcDeF0123456789A);
var_dump(-0x8000000000000000);
var_dump(is_int(0x8000000000000000));
var_dump(0x7FFFFFFFFFFFFFFF);
?>
--EXPECT--
int(9223372036854775808)
int(18446744073709551615)
int(198077019822033893530)
int(-9223372036854775808)
bool(true)
int(9223372036854775807)

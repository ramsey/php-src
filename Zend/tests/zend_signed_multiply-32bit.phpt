--TEST--
Zend signed multiply 32-bit
--SKIPIF--
<?php if (PHP_INT_SIZE != 4) print "skip Running on 64-bit target"; ?>
--FILE--
<?php
var_dump(0x8000 * -0xffff);
var_dump(0x8001 * 0xfffe);
var_dump(0x8001 * -0xffff);
?>
--EXPECT--
int(-2147450880)
int(2147483646)
int(-2147516415)

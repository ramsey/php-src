--TEST--
substr(): a bigint $offset or $length clamps to the string
--EXTENSIONS--
zend_test
--FILE--
<?php
$s = 'hello world';

// A fitting bigint offset/length behaves like the equivalent int (frameless paths).
var_dump(substr($s, zend_test_make_bigint('6')));
var_dump(substr($s, 0, zend_test_make_bigint('5')));

// A bigint offset clamps: past the end is empty, before the start is the whole string.
var_dump(substr($s, 2 ** 100));
var_dump(substr($s, -(2 ** 100)));

// A bigint length clamps: positive takes the rest, negative trims everything.
var_dump(substr($s, 0, 2 ** 100));
var_dump(substr($s, 0, -(2 ** 100)));
?>
--EXPECT--
string(5) "world"
string(5) "hello"
string(0) ""
string(11) "hello world"
string(11) "hello world"
string(0) ""

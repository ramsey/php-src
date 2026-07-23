--TEST--
bigint: serialize and unserialize round-trip boxed integers exactly
--EXTENSIONS--
zend_test
--FILE--
<?php
$v = zend_test_bigint_make('-340282366920938463463374607431768211456');
$s = serialize($v);
var_dump($s);
$r = unserialize($s);
var_dump(zend_test_int_is_boxed($r));
var_dump(zend_test_bigint_to_string($r) === zend_test_bigint_to_string($v));
var_dump(unserialize('i:99999999999999999999999999;'));
var_dump(unserialize('i:+99999999999999999999999999;'));
var_dump(serialize(['a' => $v]));
?>
--EXPECT--
string(43) "i:-340282366920938463463374607431768211456;"
bool(true)
bool(true)
int(99999999999999999999999999)
int(99999999999999999999999999)
string(57) "a:1:{s:1:"a";i:-340282366920938463463374607431768211456;}"

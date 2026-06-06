--TEST--
IS_BIGINT: assignment shares via refcount; copy and unset don't leak or crash
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = zend_test_make_bigint('9223372036854775808');
var_dump(zend_test_refcount($a) >= 1);      // the bigint zval is refcounted

$b = $a;                                    // shared via refcount (ADDREF)
$shared = zend_test_refcount($a);

unset($b);                                  // release shared ref (DELREF)
var_dump(isset($b));                        // copy is gone
var_dump($shared > zend_test_refcount($a)); // sharing had raised the count
var_dump(isset($a));                        // original survives

unset($a);                                  // final release -> rc dtor frees it
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)

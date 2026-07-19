--TEST--
bigint debug renderer: full and truncated forms
--EXTENSIONS--
zend_test
--FILE--
<?php
$v = zend_test_bigint_make(str_repeat('9', 50));
echo zend_test_int_debug_str($v, 60) . "\n";
echo zend_test_int_debug_str($v, 10) . "\n";
echo zend_test_int_debug_str(42, 10) . "\n";
?>
--EXPECT--
99999999999999999999999999999999999999999999999999
9999999999...(50 digits)
42

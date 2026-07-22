--TEST--
bigint debug renderer: full below the limit, fixed placeholder above it
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
<integer too large to display>
42

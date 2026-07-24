--TEST--
bigint: opcache flat persist copy survives freeing the original box
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_bigint_persist_roundtrip('340282366920938463463374607431768211456'));
var_dump(zend_test_bigint_persist_roundtrip('-340282366920938463463374607431768211456'));
?>
--EXPECT--
bool(true)
bool(true)

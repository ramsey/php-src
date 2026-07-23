--TEST--
bigint: strict identity for boxes
--EXTENSIONS--
zend_test
--FILE--
<?php
$x = zend_test_bigint_make('100000000000000000000');
$y = zend_test_bigint_make('100000000000000000000');
$z = zend_test_bigint_make('100000000000000000001');
$negX = zend_test_bigint_make('-100000000000000000000');

var_dump($x === $x);
var_dump($x === $y);
var_dump($x !== $y);
var_dump($x === $z);
var_dump($x !== $z);
var_dump($x === $negX);
var_dump($x !== PHP_INT_MAX);
var_dump($x === PHP_INT_MAX);
var_dump($x !== '100000000000000000000');
?>
--EXPECT--
bool(true)
bool(true)
bool(false)
bool(false)
bool(true)
bool(false)
bool(true)
bool(false)
bool(true)

--TEST--
bigint: strict identity for boxes
--FILE--
<?php
$x = 100000000000000000000;
$y = 100000000000000000000;
$z = 100000000000000000001;
$negX = -100000000000000000000;

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

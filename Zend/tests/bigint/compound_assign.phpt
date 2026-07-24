--TEST--
bigint: compound assignment boxes on overflow and demotes back without leaking
--EXTENSIONS--
zend_test
--FILE--
<?php
$x = PHP_INT_MAX;
$x += 1;
var_dump(zend_test_int_is_boxed($x));
$x -= 1;
var_dump($x === PHP_INT_MAX);
var_dump(zend_test_int_is_boxed($x));

$y = PHP_INT_MAX;
$y *= 2;
var_dump($y === zend_test_int_mul(PHP_INT_MAX, 2));

$z = 1;
$z <<= 100;
var_dump($z === zend_test_int_shift_left(1, 100));

$p = 2;
$p **= 100;
var_dump($p === zend_test_int_pow(2, 100));

/* Repeated aliasing (result === op1 === op2) is the leak canary. */
$acc = PHP_INT_MAX;
for ($i = 0; $i < 50; $i++) {
    $acc += $acc;
}
var_dump(zend_test_int_is_boxed($acc));

$acc2 = PHP_INT_MAX;
for ($i = 0; $i < 40; $i++) {
    $acc2 *= 3;
}
var_dump(zend_test_int_is_boxed($acc2));

$d = PHP_INT_MAX + 1;
$d -= 1;
var_dump(zend_test_int_is_boxed($d));
var_dump($d === PHP_INT_MAX);
?>
--EXPECT--
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)

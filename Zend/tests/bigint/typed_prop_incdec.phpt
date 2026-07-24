--TEST--
bigint: increment and decrement of a typed int property cross the long boundary
--EXTENSIONS--
zend_test
--FILE--
<?php
class C {
    public int $n = 0;
}

$c = new C();

$c->n = PHP_INT_MAX;
$c->n++;
var_dump(is_int($c->n));
var_dump(zend_test_int_is_boxed($c->n));
var_dump($c->n === PHP_INT_MAX + 1);
$c->n--;
var_dump($c->n === PHP_INT_MAX);
var_dump(zend_test_int_is_boxed($c->n));

$c->n = PHP_INT_MAX;
var_dump(zend_test_int_is_boxed(++$c->n));

$c->n = PHP_INT_MAX;
$old = $c->n++;
var_dump($old === PHP_INT_MAX);
var_dump(zend_test_int_is_boxed($old));
var_dump(zend_test_int_is_boxed($c->n));

$c->n = PHP_INT_MIN;
$c->n--;
var_dump(zend_test_int_is_boxed($c->n));
var_dump($c->n + 1 === PHP_INT_MIN);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)

--TEST--
bigint value op: add and subtract across the long boundary, canonical results
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = zend_test_bigint_make('1000000000000000000000000000000');
$b = zend_test_bigint_make('2000000000000000000000000000000');

$sum = zend_test_int_add($a, $b);
var_dump(zend_test_int_is_boxed($sum));
echo zend_test_bigint_to_string($sum) . "\n";

$zero = zend_test_int_sub($a, $a);
var_dump(zend_test_int_is_boxed($zero));
var_dump($zero);

$max = zend_test_bigint_make((string) PHP_INT_MAX);
$over = zend_test_int_add($max, 1);
var_dump(zend_test_int_is_boxed($over));
$back = zend_test_int_sub($over, $max);
var_dump($back);

$small = zend_test_int_add(2, 3);
var_dump($small);

try {
    zend_test_int_add('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_add(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_sub('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_sub(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
3000000000000000000000000000000
bool(false)
int(0)
bool(true)
int(1)
int(5)
TypeError: zend_test_int_add(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_add(): Argument #2 ($b) must be an integer
TypeError: zend_test_int_sub(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_sub(): Argument #2 ($b) must be an integer

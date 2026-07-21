--TEST--
bigint value op: multiply overflows to a box, canonical small results demote
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = zend_test_bigint_make('10000000000000000000');
$b = zend_test_int_mul($a, $a);
var_dump(zend_test_int_is_boxed($b));
echo zend_test_bigint_to_string($b) . "\n";

$max = zend_test_bigint_make((string) PHP_INT_MAX);
$two = zend_test_int_mul($max, 2);
var_dump(zend_test_int_is_boxed($two));

var_dump(zend_test_int_mul(6, 7));

try {
    zend_test_int_mul('x', 5);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_int_mul(5, 'x');
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
100000000000000000000000000000000000000
bool(true)
int(42)
TypeError: zend_test_int_mul(): Argument #1 ($a) must be an integer
TypeError: zend_test_int_mul(): Argument #2 ($b) must be an integer

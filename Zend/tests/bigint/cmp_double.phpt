--TEST--
bigint: exact bigint-double compare
--EXTENSIONS--
zend_test
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

// Naive double comparison would treat these as equal.
check('2 ** 53 + 1 <=> 2.0 ** 53', zend_test_int_cmp_double(2 ** 53 + 1, 2.0 ** 53));
check('PHP_INT_MAX <=> 9.2233720368547758E+18', zend_test_int_cmp_double(PHP_INT_MAX, 9.2233720368547758E+18));
check('2 ** 64 <=> 2.0 ** 64', zend_test_int_cmp_double(2 ** 64, 2.0 ** 64));
check('2 ** 64 + 1 <=> 2.0 ** 64', zend_test_int_cmp_double(2 ** 64 + 1, 2.0 ** 64));
check('2 ** 100 <=> INF', zend_test_int_cmp_double(2 ** 100, INF));
check('-(2 ** 100) <=> -INF', zend_test_int_cmp_double(-(2 ** 100), -INF));
check('5 <=> 5.5', zend_test_int_cmp_double(5, 5.5));
check('3000000000 <=> 3000000000.5', zend_test_int_cmp_double(3000000000, 3000000000.5));
check('-(2 ** 70) <=> 1.5', zend_test_int_cmp_double(-(2 ** 70), 1.5));

try {
    zend_test_int_cmp_double('x', 1.0);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
2 ** 53 + 1 <=> 2.0 ** 53: int(1)
PHP_INT_MAX <=> 9.2233720368547758E+18: int(-1)
2 ** 64 <=> 2.0 ** 64: int(0)
2 ** 64 + 1 <=> 2.0 ** 64: int(1)
2 ** 100 <=> INF: int(-1)
-(2 ** 100) <=> -INF: int(1)
5 <=> 5.5: int(-1)
3000000000 <=> 3000000000.5: int(-1)
-(2 ** 70) <=> 1.5: int(-1)
TypeError: zend_test_int_cmp_double(): Argument #1 ($a) must be an integer

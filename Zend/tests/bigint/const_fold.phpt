--TEST--
bigint: constant folding produces boxed constants and refuses out-of-reach folds
--EXTENSIONS--
zend_test
--FILE--
<?php
const A = 2 ** 100;
var_dump(zend_test_int_is_boxed(A));
var_dump(A === zend_test_int_pow(2, 100));

const B = 1 << 100;
var_dump(B === zend_test_int_shift_left(1, 100));

const M = (1 << 62) * 8;
var_dump(zend_test_int_is_boxed(M));
var_dump(M === zend_test_int_mul(zend_test_int_shift_left(1, 62), 8));

class K {
    const C = 2 ** 128;
}
var_dump(zend_test_int_is_boxed(K::C));
var_dump(K::C === zend_test_int_pow(2, 128));

var_dump(zend_test_int_is_boxed(2 ** 100));

/* A fold whose exponent is itself a box is out of reach; it must not fold at
 * compile time and must throw at runtime instead. */
class Reach {
    const HUGE = 2 ** (2 ** 100);
}
try {
    var_dump(Reach::HUGE);
} catch (ArithmeticError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
ArithmeticError: The libtommath bigint backend cannot raise to an exponent greater than 2147483647

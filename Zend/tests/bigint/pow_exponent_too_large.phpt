--TEST--
Bigint: a libtommath exponent beyond the backend's reach throws a catchable ArithmeticError
--EXTENSIONS--
zend_test
--SKIPIF--
<?php if (zend_test_bigint_backend() !== 'libtommath') die('skip libtommath backend only'); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// 2147483648 (INT_MAX + 1) is a valid long, but it exceeds what the libtommath
// backend can raise to (its mp_expt_n exponent is an int), so the power cannot
// be computed and an ArithmeticError is thrown.
$tooBig = 2147483648;

// Constant operands: the compiler must not fold this into an uncatchable
// compile-time error; it stays a catchable runtime ArithmeticError.
try {
    var_dump(2 ** 2147483648);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Runtime operands, long base:
try {
    var_dump(2 ** $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Bigint base with an out-of-reach exponent:
$big = PHP_INT_MAX + 1;
try {
    var_dump($big ** $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// On the thrown path the target variable keeps its old value: the compound
// assignment does not complete.
$x = 5;
try {
    $x **= $tooBig;
} catch (ArithmeticError $e) {
    // ignored
}
var_dump($x);
?>
--EXPECT--
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
int(5)

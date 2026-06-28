--TEST--
Bigint: with no memory limit, an exponent beyond the backend's reach throws a catchable ArithmeticError
--EXTENSIONS--
zend_test
--SKIPIF--
<?php if (zend_test_bigint_backend() !== 'libtommath') die('skip libtommath backend only'); ?>
--INI--
memory_limit=-1
opcache.enable_cli=0
--FILE--
<?php

$tooBig = 2147483648;
$bigExp = 2 ** 70;

try {
    var_dump(2 ** 2147483648);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

try {
    var_dump(2 ** $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

$big = PHP_INT_MAX + 1;
try {
    var_dump($big ** $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

$base = 2;
try {
    var_dump($base ** $bigExp);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

try {
    var_dump($big ** $bigExp);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

$x = 5;
try {
    $x **= $bigExp;
} catch (ArithmeticError $e) {
    // ignored
}
var_dump($x);

?>
--EXPECT--
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
The libtommath bigint backend cannot raise to an exponent greater than 2147483647
int(5)

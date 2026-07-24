--TEST--
bigint: integer exponentiation overflow promotes to a boxed integer, reach throws
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = 2 ** 200;
var_dump(is_int($a));
var_dump(zend_test_int_is_boxed($a));
var_dump($a === zend_test_int_pow(2, 200));

$r = 2 ** 100;
var_dump($r === zend_test_int_pow(2, 100));

$base = 2;
$exp = 128;
var_dump(($base ** $exp) === zend_test_int_pow(2, 128));

/* Negative exponent keeps the float path. */
var_dump(2 ** -1);
var_dump(is_float(2 ** -3));

/* A boxed exponent is out of the backend's reach and throws. */
$over = PHP_INT_MAX + 1;
try {
    $unused = 2 ** $over;
    var_dump($unused);
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
float(0.5)
bool(true)
ArithmeticError: The libtommath bigint backend cannot raise to an exponent greater than 2147483647

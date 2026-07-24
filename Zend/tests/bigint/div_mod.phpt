--TEST--
bigint: division exactness boxes, modulo boxes, zero divisor throws
--EXTENSIONS--
zend_test
--FILE--
<?php
$big = PHP_INT_MAX + 1;

/* Exact division of a box stays an integer. */
var_dump($big / 1 === $big);
var_dump(zend_test_int_is_boxed($big / 1));
var_dump(is_int($big / 2));
var_dump(zend_test_int_is_boxed($big / 2));

/* Inexact division of a box is a float. */
var_dump(is_float($big / 3));

/* PHP_INT_MIN / -1 overflows and boxes. */
$q = PHP_INT_MIN / -1;
var_dump(zend_test_int_is_boxed($q));
var_dump($q === PHP_INT_MAX + 1);
var_dump($q === -PHP_INT_MIN);

/* Modulo with a boxed operand. */
$m = $big % 7;
var_dump($m === zend_test_int_mod($big, 7));
var_dump(($big % $big) === 0);

/* intdiv is a builtin and is unaffected. */
var_dump(intdiv(10, 3));

try {
    $r = $big / 0;
    var_dump($r);
} catch (DivisionByZeroError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    $r = $big % 0;
    var_dump($r);
} catch (DivisionByZeroError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
int(3)
DivisionByZeroError: Division by zero
DivisionByZeroError: Modulo by zero

--TEST--
bigint: left shift overflow boxes, right shift saturates, negative count throws
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = 1 << 100;
var_dump(is_int($a));
var_dump(zend_test_int_is_boxed($a));
var_dump($a === zend_test_int_shift_left(1, 100));
var_dump($a > PHP_INT_MAX);

/* A shift that overflows zend_long below the word width still boxes. */
$b = 1 << 63;
var_dump(zend_test_int_is_boxed($b));
var_dump($b === zend_test_int_shift_left(1, 63));
var_dump($b > PHP_INT_MAX);

$c = 2 << 62;
var_dump($c === zend_test_int_shift_left(2, 62));

$n = 63;
$d = 1 << $n;
var_dump($d === $b);

/* Right shift saturates once the count passes every bit. */
var_dump($a >> 200);
var_dump((-$a) >> 200);
var_dump(($a >> 5) === zend_test_int_shift_right($a, 5));

/* A negative count throws, as before. */
try {
    $r = 1 << -1;
    var_dump($r);
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
bool(true)
int(0)
int(-1)
bool(true)
ArithmeticError: Bit shift by negative number

--TEST--
intdiv() with bigint operands
--EXTENSIONS--
zend_test
--FILE--
<?php

// Basic bigint division
var_dump(intdiv(2 ** 100, 2 ** 50) === 2 ** 50);

// 2 ** 100 / 3 = 422550200076076467165567735125 remainder 1
// Derived via bcmath: bcdiv('1267650600228229401496703205376', '3', 0)
// Verification: 422550200076076467165567735125 * 3 + 1 = 1267650600228229401496703205376 = 2 ** 100
$q = intdiv(2 ** 100, 3);
var_dump($q);

// Negative truncation toward zero
$qneg = intdiv(-(2 ** 100), 3);
var_dump($qneg);
var_dump($qneg === -$q);

// Small numerator, huge denominator: truncates to 0
var_dump(intdiv(5, 2 ** 100));
var_dump(intdiv(-5, 2 ** 100));

// Divide by -1: negation
var_dump(intdiv(2 ** 100, -1));

// Demotion: result fits in a long -> must be IS_LONG (strict-equal to 2)
$demoted = intdiv(2 ** 100, 2 ** 99);
var_dump($demoted === 2);

// DivisionByZeroError: long zero
try {
    intdiv(2 ** 100, 0);
} catch (DivisionByZeroError $e) {
    echo $e->getMessage() . "\n";
}

// DivisionByZeroError: non-canonical bigint zero
try {
    intdiv(5, zend_test_make_bigint('0'));
} catch (DivisionByZeroError $e) {
    echo $e->getMessage() . "\n";
}

// Named arguments
var_dump(intdiv(num2: 3, num1: 2 ** 100));

// Weak coercion: string operands still work
var_dump(intdiv('10', '3'));

?>
--EXPECT--
bool(true)
int(422550200076076467165567735125)
int(-422550200076076467165567735125)
bool(true)
int(0)
int(0)
int(-1267650600228229401496703205376)
bool(true)
Division by zero
Division by zero
int(422550200076076467165567735125)
int(3)

--TEST--
bigint: integer operators keep a box exact when the other operand is a non-int scalar
--EXTENSIONS--
zend_test
--FILE--
<?php
$box = PHP_INT_MAX + 1;

function check(string $label, $got, $want): void {
    $ok = $got === $want;
    echo $label . ': ' . ($ok ? 'ok' : 'FAIL') . "\n";
    if (!$ok) {
        var_dump($got, $want);
    }
}

/* The box stays exact; only the scalar converts. 2**63 % 5 == 2**31 % 5 == 3,
 * so a saturated box (which would give 2 on 64-bit) is ruled out on both arches. */
check('box % "5"', $box % '5', 3);
check('box % "5" vs value op', $box % '5', zend_test_int_mod($box, 5));
check('"5" % box', '5' % $box, 5);
check('"5" % box vs value op', '5' % $box, zend_test_int_mod(5, $box));

check('box & "6"', $box & '6', 0);
check('box & "6" vs value op', $box & '6', zend_test_int_and($box, 6));
check('box & true', $box & true, 0);
check('box & 6.0', $box & 6.0, 0);

check('box | "1"', $box | '1', zend_test_int_or($box, 1));
check('box | "1" == box+1', $box | '1', $box + 1);
check('"1" | box', '1' | $box, zend_test_int_or($box, 1));

check('box ^ "3"', $box ^ '3', zend_test_int_xor($box, 3));

check('box << "2"', $box << '2', zend_test_int_shift_left($box, 2));
check('box << "2" boxed', zend_test_int_is_boxed($box << '2'), true);
check('box >> "1"', $box >> '1', zend_test_int_shift_right($box, 1));

/* Coerced divisor of zero still throws, box dividend intact. */
try {
    $r = $box % '0';
    var_dump($r);
} catch (DivisionByZeroError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

/* Coerced negative count still throws. */
try {
    $r = $box << '-1';
    var_dump($r);
} catch (ArithmeticError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

/* A non-numeric string still throws today's TypeError. */
try {
    $r = $box % 'abc';
    var_dump($r);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
box % "5": ok
box % "5" vs value op: ok
"5" % box: ok
"5" % box vs value op: ok
box & "6": ok
box & "6" vs value op: ok
box & true: ok
box & 6.0: ok
box | "1": ok
box | "1" == box+1: ok
"1" | box: ok
box ^ "3": ok
box << "2": ok
box << "2" boxed: ok
box >> "1": ok
DivisionByZeroError: Modulo by zero
ArithmeticError: Bit shift by negative number
TypeError: Unsupported operand types: int % string

--TEST--
bigint: integer operators keep a box exact when the other operand is a non-int scalar
--FILE--
<?php
$box = 340282366920938463463374607431768211456;

function check(string $label, $got, $want): void {
    $ok = $got === $want;
    echo $label . ': ' . ($ok ? 'ok' : 'FAIL') . "\n";
    if (!$ok) {
        var_dump($got, $want);
    }
}

/* The box stays exact; only the scalar operand converts to int. */
check('box % "5"', $box % '5', 1);
check('"5" % box', '5' % $box, 5);

check('box & "6"', $box & '6', 0);
check('box & true', $box & true, 0);
check('box & 6.0', $box & 6.0, 0);

check('box | "1"', $box | '1', 340282366920938463463374607431768211457);
check('box | "1" == box + 1', $box | '1', $box + 1);
check('"1" | box', '1' | $box, 340282366920938463463374607431768211457);

check('box ^ "3"', $box ^ '3', 340282366920938463463374607431768211459);
check('box ^ "3" == box + 3', $box ^ '3', $box + 3);

check('box << "2"', $box << '2', 1361129467683753853853498429727072845824);
check('box >> "1"', $box >> '1', 170141183460469231731687303715884105728);

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
"5" % box: ok
box & "6": ok
box & true: ok
box & 6.0: ok
box | "1": ok
box | "1" == box + 1: ok
"1" | box: ok
box ^ "3": ok
box ^ "3" == box + 3: ok
box << "2": ok
box >> "1": ok
DivisionByZeroError: Modulo by zero
ArithmeticError: Bit shift by negative number
TypeError: Unsupported operand types: int % string

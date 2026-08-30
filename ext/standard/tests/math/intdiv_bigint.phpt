--TEST--
intdiv computes with full precision
--FILE--
<?php
var_dump(intdiv(PHP_INT_MIN, -1) === -1 * PHP_INT_MIN);
var_dump(intdiv(10 ** 30, 10 ** 15) === 10 ** 15);
var_dump(intdiv(-7, 2) === -3);
var_dump(intdiv(0 - 10 ** 30 - 7, 10 ** 15) === -1 * 10 ** 15);
var_dump(intdiv(10 ** 30 + 7, -1 * 10 ** 15) === -1 * 10 ** 15);
var_dump(intdiv(5, 10 ** 30) === 0);
var_dump(intdiv('5', 2));
try {
    intdiv(10 ** 30, 0);
} catch (DivisionByZeroError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
eval('declare(strict_types=1); var_dump(intdiv(10 ** 30, 10 ** 15) === 10 ** 15);');
try {
    eval("declare(strict_types=1); intdiv('5', 2);");
} catch (TypeError $e) {
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
int(2)
DivisionByZeroError: Division by zero
bool(true)
TypeError: intdiv(): Argument #1 ($num1) must be of type int, string given

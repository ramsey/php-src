--TEST--
bigint: shift operators promote over-width and string counts
--FILE--
<?php
var_dump(1 << 100);
var_dump(1 << '100');
var_dump(1 << 100.0);
var_dump(-1 >> '100');
var_dump(0 >> '100');

try {
    $r = 1 << '-1';
    var_dump($r);
} catch (ArithmeticError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    $r = 1 << '99999999999999999999';
    var_dump($r);
} catch (ArithmeticError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
int(-1)
int(0)
ArithmeticError: Bit shift by negative number
ArithmeticError: The libtommath bigint backend cannot shift left by more than %d bits

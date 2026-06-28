--TEST--
Bigint: a left shift whose result exceeds memory_limit throws a catchable ArithmeticError
--INI--
memory_limit=64M
opcache.enable_cli=0
--FILE--
<?php

// INT_MAX is within the backend's shift range, so the shift is permitted, but
// the result (~268 MB) cannot fit memory_limit. Previously this was an
// uncatchable fatal.
$count = 2147483647;

// Long operand.
$max = PHP_INT_MAX;
try {
    $x = $max << $count;
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

// Bigint operand.
$big = 2 ** 100;
try {
    $x = $big << $count;
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

$shift = 100;
var_dump(1 << $shift);

?>
--EXPECT--
Bit shift produces an integer too large to fit in the configured memory limit
Bit shift produces an integer too large to fit in the configured memory limit
int(1267650600228229401496703205376)

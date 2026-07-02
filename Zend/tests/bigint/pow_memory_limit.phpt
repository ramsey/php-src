--TEST--
Bigint: integer exponentiation refuses a result too large for memory_limit
--INI--
memory_limit=128M
opcache.enable_cli=0
--FILE--
<?php

$base = 23;
$exp = 2147483647;

try {
    $r = $base ** $exp;
    echo "no throw (operator)\n";
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

try {
    $r = pow($base, $exp);
    echo "no throw (pow)\n";
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

$bigbase = 2 ** 70;
try {
    $r = $bigbase ** $exp;
    echo "no throw (bigint base)\n";
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

$over = 2147483648;
try {
    $r = 2 ** $over;
    echo "no throw (over INT_MAX)\n";
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

// |base| <= 1 never overflows, so it still computes exactly.
var_dump(1 ** $exp);
var_dump(0 ** $exp);

// Powers that fit the limit still compute and stay integers.
var_dump(is_int(2 ** 1000));
var_dump(is_int(2 ** 100000));

?>
--EXPECT--
Exponentiation produces an integer too large to fit in the configured memory limit
Exponentiation produces an integer too large to fit in the configured memory limit
Exponentiation produces an integer too large to fit in the configured memory limit
Exponentiation produces an integer too large to fit in the configured memory limit
int(1)
int(0)
bool(true)
bool(true)

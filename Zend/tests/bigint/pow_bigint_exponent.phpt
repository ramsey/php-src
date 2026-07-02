--TEST--
Bigint: a bigint exponent yields exact int for base 0/1/-1, float for negative exponent, and catchable error otherwise
--INI--
memory_limit=128M
opcache.enable_cli=0
--FILE--
<?php

$bigEven = 2 ** 70;
$bigOdd  = 2 ** 70 + 1;

var_dump(0 ** $bigEven);
var_dump(1 ** $bigEven);
var_dump((-1) ** $bigEven);
var_dump((-1) ** $bigOdd);

var_dump(pow(1, $bigEven));
var_dump(pow(-1, $bigOdd));

var_dump(1 ** (-$bigEven));
var_dump(2 ** (-$bigEven));

$x = -1;
$x **= $bigEven;
var_dump($x);

$base = 2;
try {
    var_dump($base ** $bigEven);
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

$y = 5;
try {
    $y **= $bigEven;
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}
var_dump($y);

?>
--EXPECT--
int(0)
int(1)
int(1)
int(-1)
int(1)
int(-1)
float(1)
float(0)
int(1)
Exponentiation produces an integer too large to fit in the configured memory limit
Exponentiation produces an integer too large to fit in the configured memory limit
int(5)

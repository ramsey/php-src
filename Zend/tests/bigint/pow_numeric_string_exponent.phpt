--TEST--
Bigint: an out-of-range numeric-string exponent behaves like a bigint exponent
--INI--
memory_limit=128M
opcache.enable_cli=0
--FILE--
<?php

$strExp = '1180591620717411303424';

var_dump(0 ** $strExp);
var_dump(1 ** $strExp);
var_dump((-1) ** $strExp);

$base = 2;
try {
    var_dump($base ** $strExp);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

$strBase = '5';
try {
    var_dump($strBase ** $strExp);
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
int(0)
int(1)
int(1)
Exponentiation produces an integer too large to fit in the configured memory limit
Exponentiation produces an integer too large to fit in the configured memory limit

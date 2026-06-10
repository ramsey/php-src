--TEST--
Bigint: weak-mode float coercion of big integers (regression guard)
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Intentionally no declare(strict_types=1). These must keep working in weak mode.

function fw(float $x): float {
    return $x;
}

// Large bigint -> float in weak mode
var_dump(fw(2 ** 100));

// Internal float function in weak mode
var_dump(sqrt(2 ** 100));

// In-range bigint -> float in weak mode
var_dump(fw(zend_test_make_bigint('9')));
?>
--EXPECT--
float(1.2676506002282294E+30)
float(1125899906842624)
float(9)

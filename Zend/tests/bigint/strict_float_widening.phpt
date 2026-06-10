--TEST--
Bigint: strict-mode float targets widen big integers like longs (SSTH exception)
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php
declare(strict_types=1);

// === Internal function: float param in strict mode ===
// sqrt() takes a float; a bigint is an int, so SSTH exception must widen it.
$big = 2 ** 100;
var_dump(sqrt($big));

// === Userland function: float param in strict mode ===
function f(float $x): float {
    return $x;
}

// Large bigint -> widened to float
var_dump(f(2 ** 100));

// In-range bigint -> widened to float
var_dump(f(zend_test_make_bigint('5')));

// === Userland function: float return type in strict mode ===
function g(): float {
    return 2 ** 100;
}
var_dump(g());

// === Typed property: float property assigned a bigint in strict mode ===
class C {
    public float $f = 0.0;
}
$c = new C();
$c->f = 2 ** 100;
var_dump($c->f);

// In-range bigint to float property
$c->f = zend_test_make_bigint('7');
var_dump($c->f);

// === Float-typed reference: bigint must widen ===
$c->f = 0.0;
$r = &$c->f;
$r = 2 ** 100;
var_dump($c->f);

// === int|float param in strict mode: bigint STAYS int (not coerced to float) ===
function h(int|float $x): mixed {
    return $x;
}
$result = h(2 ** 100);
var_dump(is_int($result));
var_dump($result);

?>
--EXPECT--
float(1125899906842624)
float(1.2676506002282294E+30)
float(5)
float(1.2676506002282294E+30)
float(1.2676506002282294E+30)
float(7)
float(1.2676506002282294E+30)
bool(true)
int(1267650600228229401496703205376)

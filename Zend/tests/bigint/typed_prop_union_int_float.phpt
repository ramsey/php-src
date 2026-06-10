--TEST--
Bigint: int|float typed property does not coerce to float when assigned a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php
class C {
    public int|float $n;
}

// Weak mode: bigint assigned to int|float should stay int, not coerce to float.
$c = new C();
$c->n = 2 ** 100;
var_dump(is_int($c->n));
var_dump(is_float($c->n));
var_dump($c->n);

// Negative bigint remains int and does not coerce to float.
$c = new C();
$c->n = -2 ** 100;
var_dump(is_int($c->n));
var_dump(is_float($c->n));
var_dump($c->n);
?>
--EXPECT--
bool(true)
bool(false)
int(1267650600228229401496703205376)
bool(true)
bool(false)
int(-1267650600228229401496703205376)

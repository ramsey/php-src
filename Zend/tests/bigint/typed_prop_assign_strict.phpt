--TEST--
Bigint: int-typed property accepts and keeps a big integer (strict mode)
--INI--
opcache.enable_cli=0
--FILE--
<?php
declare(strict_types=1);

class C {
    public int $n;
}

$c = new C();

$c->n = 2 ** 100;
var_dump($c->n);
var_dump(is_int($c->n));

// Assign back to a standard positive integer value.
$c->n = 42;
var_dump($c->n);
var_dump(is_int($c->n));

// Re-assign a positive bigint.
$c->n = 2 ** 128 - 1;
var_dump($c->n);
var_dump(is_int($c->n));

// Assign back to a standard negative integer value.
$c->n = -42;
var_dump($c->n);
var_dump(is_int($c->n));

// Re-assign a negative bigint.
$c->n = -2 ** 127;
var_dump($c->n);
var_dump(is_int($c->n));
?>
--EXPECT--
int(1267650600228229401496703205376)
bool(true)
int(42)
bool(true)
int(340282366920938463463374607431768211455)
bool(true)
int(-42)
bool(true)
int(-170141183460469231731687303715884105728)
bool(true)

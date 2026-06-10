--TEST--
Bigint: typed reference accepts and keeps a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php
class C {
    public int $n = 0;
}

$c = new C();

// Assign through a typed reference.
$r =& $c->n;
$r = 2 ** 100;
var_dump($c->n);
var_dump(is_int($c->n));
var_dump($r === $c->n);

// Arithmetic through the reference must keep the bigint.
$r += 1;
var_dump($c->n);
var_dump(is_int($c->n));
?>
--EXPECT--
int(1267650600228229401496703205376)
bool(true)
bool(true)
int(1267650600228229401496703205377)
bool(true)

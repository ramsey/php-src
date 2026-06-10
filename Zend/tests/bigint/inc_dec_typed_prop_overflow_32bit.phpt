--TEST--
Bigint: ++/-- on a typed int property promotes past the long boundary
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
class C {
    public int $n;
}

$c = new C();

// Post-increment: property promotes to bigint, return value is the old long.
$c->n = PHP_INT_MAX;
$ret = $c->n++;
var_dump($ret);     // old value
var_dump($c->n);    // promoted bigint

// Post-decrement: property promotes to bigint, return value is the old long.
$c->n = PHP_INT_MIN;
$ret = $c->n--;
var_dump($ret);     // old value
var_dump($c->n);    // promoted bigint

// Pre-increment: property promotes to bigint, return value is the new bigint.
$c->n = PHP_INT_MAX;
$ret = ++$c->n;
var_dump($ret);             // new bigint
var_dump($ret === $c->n);   // same value
var_dump($c->n);            // promoted bigint

// Pre-decrement: property promotes to bigint, return value is the new bigint.
$c->n = PHP_INT_MIN;
$ret = --$c->n;
var_dump($ret);             // new bigint
var_dump($ret === $c->n);   // same value
var_dump($c->n);            // promoted bigint
?>
--EXPECT--
int(2147483647)
int(2147483648)
int(-2147483648)
int(-2147483649)
int(2147483648)
bool(true)
int(2147483648)
int(-2147483649)
bool(true)
int(-2147483649)

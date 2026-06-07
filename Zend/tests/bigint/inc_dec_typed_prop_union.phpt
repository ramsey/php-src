--TEST--
Bigint: ++/-- overflow on an int|float typed property stays a big integer
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// An int|float slot accepts int, so integer overflow must promote to a big
// integer rather than silently degrade to float.

class C {
    public int|float $n;
}

$c = new C();

$c->n = PHP_INT_MAX;
$c->n++;
var_dump(is_int($c->n), is_double($c->n), $c->n);

$c->n = PHP_INT_MIN;
$c->n--;
var_dump(is_int($c->n), is_double($c->n), $c->n);

// Pre-increment returning the value behaves the same.
$c->n = PHP_INT_MAX;
$r = ++$c->n;
var_dump(is_int($r), $r === $c->n, $c->n);

// Binding a reference makes the slot a typed reference, which routes ++/-- through
// the typed-ref path; the bigint must survive and be visible through the alias.
$c->n = PHP_INT_MAX;
$ref =& $c->n;
$c->n++;
var_dump(is_int($ref), $ref, $ref === $c->n);

?>
--EXPECT--
bool(true)
bool(false)
int(9223372036854775808)
bool(true)
bool(false)
int(-9223372036854775809)
bool(true)
bool(true)
int(9223372036854775808)
bool(true)
int(9223372036854775808)
bool(true)

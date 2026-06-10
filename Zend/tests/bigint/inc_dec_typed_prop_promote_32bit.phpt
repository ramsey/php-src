--TEST--
Bigint: ++/-- on typed ref and static int property promotes to bigint
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
class C {
    public int $n;
    public static int $s;
}

$c = new C();

// Typed reference: $r aliases an int-typed property; ++ must promote.
$c->n = PHP_INT_MAX;
$r =& $c->n;
$r++;
var_dump(is_int($r));
var_dump($r);
var_dump(is_int($c->n));
var_dump($c->n === $r);

// Pre-increment through a typed reference.
$c->n = PHP_INT_MAX;
$r =& $c->n;
++$r;
var_dump(is_int($r));
var_dump($r);
var_dump(is_int($c->n));
var_dump($c->n === $r);

// Post-decrement from PHP_INT_MIN through a typed reference.
$c->n = PHP_INT_MIN;
$r =& $c->n;
$r--;
var_dump(is_int($r));
var_dump($r);
var_dump(is_int($c->n));
var_dump($c->n === $r);

// Pre-decrement from PHP_INT_MIN through a typed reference.
$c->n = PHP_INT_MIN;
$r =& $c->n;
--$r;
var_dump(is_int($r));
var_dump($r);
var_dump(is_int($c->n));
var_dump($c->n === $r);

// Static int-typed property: post-increment promotes to bigint.
C::$s = PHP_INT_MAX;
C::$s++;
var_dump(C::$s);

// Static int-typed property: pre-increment promotes to bigint.
C::$s = PHP_INT_MAX;
++C::$s;
var_dump(C::$s);

// Static int-typed property: post-decrement promotes to bigint.
C::$s = PHP_INT_MIN;
C::$s--;
var_dump(C::$s);

// Static int-typed property: pre-decrement promotes to bigint.
C::$s = PHP_INT_MIN;
--C::$s;
var_dump(C::$s);
?>
--EXPECT--
bool(true)
int(2147483648)
bool(true)
bool(true)
bool(true)
int(2147483648)
bool(true)
bool(true)
bool(true)
int(-2147483649)
bool(true)
bool(true)
bool(true)
int(-2147483649)
bool(true)
bool(true)
int(2147483648)
int(2147483648)
int(-2147483649)
int(-2147483649)

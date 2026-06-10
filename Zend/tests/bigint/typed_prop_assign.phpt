--TEST--
Bigint: int-typed property accepts and keeps a big integer (weak mode)
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Using 32-bit values, since they work on both 32-bit and 64-bit platforms.
const INT_MAX_32BIT = 2147483647;
const INT_MIN_32BIT = -2147483648;

class C {
    public int $n;
    public static int $s;
}

// Property stays an int after assignment.
$c = new C();
$c->n = 2 ** 100;
var_dump($c->n);
var_dump(is_int($c->n));

// Property stays an int after assignment (with negative bigint).
$c = new C();
$c->n = -2 ** 100;
var_dump($c->n);
var_dump(is_int($c->n));

// Static property stays an int after assignment.
C::$s = 2 ** 100;
var_dump(C::$s);
var_dump(is_int(C::$s));

// Static property stays an int after assignment (with negative bigint).
C::$s = -2 ** 100;
var_dump(C::$s);
var_dump(is_int(C::$s));

// Standard integer assignment (with 32-bit PHP_INT_MAX value).
$c->n = INT_MAX_32BIT;
var_dump($c->n);
var_dump(is_int($c->n));

// Standard integer assignment (with 32-bit PHP_INT_MIN value).
$c->n = INT_MIN_32BIT;
var_dump($c->n);
var_dump(is_int($c->n));

// Static property standard integer assignment (with 32-bit PHP_INT_MAX value).
C::$s = INT_MAX_32BIT;
var_dump(C::$s);
var_dump(is_int(C::$s));

// Static property standard integer assignment (with 32-bit PHP_INT_MIN value).
C::$s = INT_MIN_32BIT;
var_dump(C::$s);
var_dump(is_int(C::$s));

// Constructor-promoted property.
class D {
    public function __construct(public int $n) {}
}

// Constructor-promoted property stays an int after assignment.
$d = new D(2 ** 100);
var_dump($d->n);
var_dump(is_int($d->n));

// Constructor-promoted property stays an int after assignment (with negative bigint).
$d = new D(-2 ** 100);
var_dump($d->n);
var_dump(is_int($d->n));

// Constructor-promoted property standard integer assignment (with 32-bit PHP_INT_MAX value).
$d = new D(INT_MAX_32BIT);
var_dump($d->n);
var_dump(is_int($d->n));

// Constructor-promoted property standard integer assignment (with 32-bit PHP_INT_MIN value).
$d = new D(INT_MIN_32BIT);
var_dump($d->n);
var_dump(is_int($d->n));
?>
--EXPECT--
int(1267650600228229401496703205376)
bool(true)
int(-1267650600228229401496703205376)
bool(true)
int(1267650600228229401496703205376)
bool(true)
int(-1267650600228229401496703205376)
bool(true)
int(2147483647)
bool(true)
int(-2147483648)
bool(true)
int(2147483647)
bool(true)
int(-2147483648)
bool(true)
int(1267650600228229401496703205376)
bool(true)
int(-1267650600228229401496703205376)
bool(true)
int(2147483647)
bool(true)
int(-2147483648)
bool(true)

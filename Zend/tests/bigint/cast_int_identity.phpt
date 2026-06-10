--TEST--
Bigint: (int) cast is an identity; a bigint is already an int
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php

$big = 2 ** 100;

// CV (compiled variable) cast: the original variable
var_dump((int) $big === $big);

// TMP cast: temporary expression result
$a = 2 ** 50;
$b = 2 ** 50;
$tmp = (int) ($a * $b); // product is bigint 2**100
var_dump($tmp === $big);

// CONST literal that overflows long range
var_dump((int) 9223372036854775808 === 9223372036854775808);

// Reference: deref then identity
$x = 2 ** 100;
$r =& $x;
$y = (int) $r;
var_dump($x, $y);

// No use-after-free: original is still live after cast
$orig = 2 ** 100;
$cast = (int) $orig;
var_dump($orig);
var_dump($cast);

// In-range bigint via zend_test helper
$small = zend_test_make_bigint('5');
var_dump(gettype($small));
var_dump((int) $small === $small);

// Pin: (float) on a bigint still converts to float
$f = (float) $big;
var_dump(is_float($f));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
string(7) "integer"
bool(true)
bool(true)

--TEST--
Bigint: weak string targets accept big integers, coercing to the decimal string
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php

// Internal function weak string param (Z_PARAM_STR)
var_dump(strlen(2 ** 100));
var_dump(strrev(2 ** 100));

// In-range bigint (zend_test_make_bigint) coerces to its decimal string.
$b = zend_test_make_bigint('256');
var_dump(strlen($b));
var_dump(strrev($b));

// Userland weak string param
function f(string $s): string {
    return $s;
}
var_dump(f(2 ** 100));

// String-typed property weak assignment
class C {
    public string $s;
}
$o = new C();
$o->s = 2 ** 100;
var_dump($o->s);

// Union and nullable targets

// string | int prefers the int leg: the bigint stays an int.
function g(string | int $v): string | int {
    return $v;
}
var_dump(g(2 ** 100));

// ?string coerces like plain string; null still passes through.
function h(?string $s): ?string {
    return $s;
}
var_dump(h(2 ** 100));
var_dump(h(null));

// Concat sanity pin (string conversion already works)
var_dump('x' . (2 ** 100));

?>
--EXPECT--
int(31)
string(31) "6735023076941049228220060567621"
int(3)
string(3) "652"
string(31) "1267650600228229401496703205376"
string(31) "1267650600228229401496703205376"
int(1267650600228229401496703205376)
string(31) "1267650600228229401496703205376"
NULL
string(32) "x1267650600228229401496703205376"

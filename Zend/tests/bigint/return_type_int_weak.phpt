--TEST--
Bigint: weak-mode int return types accept and keep big integers
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php

// Weak mode: plain int return via expression
function weak_int_return_expr(): int {
    return 2 ** 100;
}
var_dump(weak_int_return_expr());

// Weak mode: plain int return via variable
function weak_int_return_var(): int {
    $x = 2 ** 100;
    return $x;
}
var_dump(weak_int_return_var());

// Weak mode: nullable ?int return
function weak_nullable_int_return(): ?int {
    return 2 ** 100;
}
var_dump(weak_nullable_int_return());

// Weak mode: in-range bigint via zend_test_make_bigint
function weak_in_range_bigint_return(): int {
    return zend_test_make_bigint('5');
}
var_dump(weak_in_range_bigint_return());

?>
--EXPECT--
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
int(5)

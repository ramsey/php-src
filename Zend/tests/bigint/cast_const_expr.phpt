--TEST--
Bigint: (int) in a compile-time constant expression preserves full precision
--INI--
opcache.enable_cli=0
--FILE--
<?php

// Constant expression: the cast is a no-op identity
class C {
    const X = (int) (10 ** 30);
}
var_dump(C::X);

// Also check a global const
const BIG = (int) (2 ** 100);
var_dump(BIG);

// Runtime constant-expression path: B::Y references A::BASE from another class,
// which defeats compile-time folding so zend_ast evaluates the cast at runtime.
class A {
    const BASE = 10 ** 30;
}
class B {
    const Y = (int) (A::BASE);
}
var_dump(B::Y);

// Default-parameter case; also routes through runtime evaluation.
function f($x = (int) (A::BASE)) {
    return $x;
}
var_dump(f());

?>
--EXPECT--
int(1000000000000000000000000000000)
int(1267650600228229401496703205376)
int(1000000000000000000000000000000)
int(1000000000000000000000000000000)

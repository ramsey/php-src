--TEST--
Bigint: a bigint literal in an unevaluated constant expression exports as its digits
--INI--
zend.assertions=1
--FILE--
<?php

// Reflection of a deferred constant-expression default that embeds a bigint literal.
const C = 1;
function f($x = 99999999999999999999 + C) {}
echo new ReflectionParameter('f', 'x') . "\n";

// A failed assert() builds its description message from the original expression AST.
try {
    assert(99999999999999999999 === 5);
} catch (AssertionError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
Parameter #0 [ <optional> $x = 99999999999999999999 + C ]
assert(99999999999999999999 === 5)

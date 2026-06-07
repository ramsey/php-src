--TEST--
Bigint: ++/-- on a typed int property at the boundary errors without leaking (temporary test)
--INI--
opcache.enable_cli=0
--FILE--
<?php
class C {
    public int $n;
}

$c = new C();
$c->n = PHP_INT_MAX;
try {
    $c->n++;
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
var_dump($c->n);

$c->n = PHP_INT_MIN;
try {
    $c->n--;
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
var_dump($c->n);
?>
--EXPECT--
Cannot increment property C::$n of type int past its maximal value
int(9223372036854775807)
Cannot decrement property C::$n of type int past its minimal value
int(-9223372036854775808)

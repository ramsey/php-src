--TEST--
Test typed int property does not overflow to float
--SKIPIF--
<?php if (PHP_INT_SIZE == 4) die("SKIP: 64 bit test"); ?>
--FILE--
<?php

class Foo {
    public static int $bar = PHP_INT_MAX;
};

Foo::$bar++;
var_dump(Foo::$bar);

Foo::$bar += 1;
var_dump(Foo::$bar);

++Foo::$bar;
var_dump(Foo::$bar);

Foo::$bar = Foo::$bar + 1;
var_dump(Foo::$bar);

?>
--EXPECT--
int(9223372036854775808)
int(9223372036854775809)
int(9223372036854775810)
int(9223372036854775811)

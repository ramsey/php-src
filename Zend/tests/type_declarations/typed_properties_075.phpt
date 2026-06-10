--TEST--
Test typed properties overflowing (64 bit)
--SKIPIF--
<?php if (PHP_INT_SIZE == 4) die("SKIP: 64 bit test"); ?>
--FILE--
<?php

class Foo {
    public static int $bar = PHP_INT_MAX;
};

try {
    Foo::$bar++;
} catch(TypeError $t) {
    var_dump($t->getMessage());
}

var_dump(Foo::$bar);

try {
    Foo::$bar += 1;
} catch(TypeError $t) {
    var_dump($t->getMessage());
}

var_dump(Foo::$bar);

try {
    ++Foo::$bar;
} catch(TypeError $t) {
    var_dump($t->getMessage());
}

var_dump(Foo::$bar);

try {
    Foo::$bar = Foo::$bar + 1;
} catch(TypeError $t) {
    var_dump($t->getMessage());
}

var_dump(Foo::$bar);

?>
--EXPECT--
int(9223372036854775808)
int(9223372036854775809)
int(9223372036854775810)
int(9223372036854775811)

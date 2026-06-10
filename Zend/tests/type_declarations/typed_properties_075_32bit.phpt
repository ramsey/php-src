--TEST--
Test typed properties overflowing (32 bit)
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("SKIP: 32 bit test"); ?>
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
int(2147483649)
int(2147483649)
int(2147483650)
int(2147483651)

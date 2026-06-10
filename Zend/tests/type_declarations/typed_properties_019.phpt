--TEST--
Test typed properties int promotes to bigint on overflow
--FILE--
<?php
class Foo {
    public int $bar = PHP_INT_MAX;

    public function inc() {
        return ++$this->bar;
    }
}

$foo = new Foo();

$ret = $foo->inc();
var_dump(is_int($ret));
var_dump(is_int($foo->bar));
?>
--EXPECT--
bool(true)
bool(true)

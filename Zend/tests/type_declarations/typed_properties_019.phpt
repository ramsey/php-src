--TEST--
Test typed properties int does not overflow to float
--FILE--
<?php
class Foo {
    public int $bar = PHP_INT_MAX;

    public function inc() {
        return ++$this->bar;
    }
}

$foo = new Foo();
$foo->inc();
var_dump(is_int($foo->bar));
?>
--EXPECT--
bool(true)

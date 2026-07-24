--TEST--
Test typed int property does not overflow to float
--FILE--
<?php

$foo = new class {
    public int $bar = PHP_INT_MAX;
};

$foo->bar++;
var_dump($foo);

$foo->bar += 1;
var_dump($foo);

++$foo->bar;
var_dump($foo);

$foo->bar = $foo->bar + 1;
var_dump($foo);

?>
--EXPECTF--
object(class@anonymous)#1 (1) {
  ["bar"]=>
  int(%d)
}
object(class@anonymous)#1 (1) {
  ["bar"]=>
  int(%d)
}
object(class@anonymous)#1 (1) {
  ["bar"]=>
  int(%d)
}
object(class@anonymous)#1 (1) {
  ["bar"]=>
  int(%d)
}

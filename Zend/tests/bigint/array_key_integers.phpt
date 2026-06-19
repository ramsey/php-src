--TEST--
bigint: integer and string decimal array keys are treated as integers
--FILE--
<?php
$keys = [
    (string) PHP_INT_MAX,
    PHP_INT_MAX,
    (string) (PHP_INT_MAX + 1),
    PHP_INT_MAX + 1,
    (string) PHP_INT_MIN,
    PHP_INT_MIN,
    (string) (PHP_INT_MIN - 1),
    PHP_INT_MIN - 1,
    (string) (-PHP_INT_MAX - 1),
    -PHP_INT_MAX - 1,
];

var_dump(array_fill_keys($keys, 1));
?>
--EXPECTF--
array(4) {
  [%d7]=>
  int(1)
  [%d8]=>
  int(1)
  [-%d8]=>
  int(1)
  [-%d9]=>
  int(1)
}

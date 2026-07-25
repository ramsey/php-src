--TEST--
bigint: is_numeric accepts boxed ints and agrees with is_int
--FILE--
<?php
$literal = 123456789012345678901234567890;
$overflow = PHP_INT_MAX + 1;
$negative = -(2 ** 70);

var_dump(is_numeric($literal));
var_dump(is_numeric($overflow));
var_dump(is_numeric($negative));

$cases = [
    'literal' => $literal,
    'overflow' => $overflow,
    'negative' => $negative,
];

foreach ($cases as $name => $value) {
    echo $name . ': is_numeric=' . var_export(is_numeric($value), true)
        . ' is_int=' . var_export(is_int($value), true) . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
literal: is_numeric=true is_int=true
overflow: is_numeric=true is_int=true
negative: is_numeric=true is_int=true

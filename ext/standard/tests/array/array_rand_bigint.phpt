--TEST--
array_rand(): a big-integer $num is rejected by the element-count limit, not the int range
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = [10, 20, 30];

// In-range bigint count returns that many distinct, valid keys.
$keys = array_rand($a, zend_test_make_bigint('2'));
var_dump(count($keys) === 2);
var_dump($keys[0] !== $keys[1]);
var_dump(in_array($keys[0], [0, 1, 2], true) && in_array($keys[1], [0, 1, 2], true));

// Big integers exceed the element count, so both signs hit the count limit.
try {
    array_rand($a, 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

try {
    array_rand($a, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
array_rand(): Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)
array_rand(): Argument #2 ($num) must be between 1 and the number of elements in argument #1 ($array)

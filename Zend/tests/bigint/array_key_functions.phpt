--TEST--
Bigint: array_keys/array_flip/array_search/array_column handle bigint keys
--FILE--
<?php
$a = [];
$a[2 ** 100] = 'x';
$a[5] = 'y';

// array_keys() yields the bigint key as an int.
$keys = array_keys($a);
var_dump($keys[0] === 2 ** 100);
var_dump(is_int($keys[0]));
var_dump($keys[1] === 5);

// array_keys() with a search value.
$keys2 = array_keys($a, 'x');
var_dump($keys2[0] === 2 ** 100);

// array_flip() turns the bigint key into an int value.
$flipped = array_flip($a);
var_dump($flipped['x'] === 2 ** 100);
var_dump(is_int($flipped['x']));

// array_search() returns the bigint key.
var_dump(array_search('x', $a) === 2 ** 100);

// array_column() with a bigint index column.
$records = [['id' => 2 ** 100, 'v' => 'z']];
$col = array_column($records, 'v', 'id');
var_dump($col[2 ** 100] === 'z');
var_dump(array_key_first($col) === 2 ** 100);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

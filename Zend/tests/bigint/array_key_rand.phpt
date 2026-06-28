--TEST--
Bigint: array_rand() returns an out-of-range key as an int
--FILE--
<?php

echo "single pick:\n";
$one = [2 ** 100 => 'x'];
$k = array_rand($one);
var_dump(is_int($k));
var_dump($k === 2 ** 100);

echo "multi pick:\n";
$two = [2 ** 100 => 'x', 2 ** 101 => 'y'];
$keys = array_rand($two, 2);
var_dump(is_int($keys[0]));
var_dump(is_int($keys[1]));
sort($keys);
var_dump($keys === [2 ** 100, 2 ** 101]);

// See if we can surface any memory leaks in the debug-build memory report.
for ($i = 0; $i < 1000; $i++) {
    array_rand($one);
    array_rand($two, 2);
}

?>
--EXPECT--
single pick:
bool(true)
bool(true)
multi pick:
bool(true)
bool(true)
bool(true)

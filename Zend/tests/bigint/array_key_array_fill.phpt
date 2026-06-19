--TEST--
Bigint: array_fill() accepts a bigint $start_key
--FILE--
<?php
// A bigint start key produces consecutive int (bigint) keys.
$a = array_fill(2 ** 100, 3, 'x');
var_dump(count($a));
$keys = array_keys($a);
var_dump($keys[0] === 2 ** 100);
var_dump($keys[1] === 2 ** 100 + 1);
var_dump($keys[2] === 2 ** 100 + 2);
var_dump(is_int($keys[0]));
var_dump($a[2 ** 100]);
var_dump($a[2 ** 100 + 2]);

// Negative bigint start starts at the negative value and produces consecutive
// int (bigint) keys.
$b = array_fill(-(2 ** 100), 5, 'y');
var_dump(count($b));
$keys = array_keys($b);
var_dump($keys[0] === -(2 ** 100));
var_dump($keys[1] === -(2 ** 100) + 1);
var_dump($keys[2] === -(2 ** 100) + 2);
var_dump($keys[3] === -(2 ** 100) + 3);
var_dump($keys[4] === -(2 ** 100) + 4);
var_dump(is_int($keys[0]));
var_dump($b[-(2 ** 100)]);
var_dump($b[-(2 ** 100) + 2]);

// Zero count yields an empty array even with a bigint start.
var_dump(array_fill(2 ** 100, 0, 'x'));
var_dump(array_fill(-(2 ** 100), 0, 'x'));

// Negative count still errors.
try {
    array_fill(2 ** 100, -1, 'x');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
int(3)
bool(true)
bool(true)
bool(true)
bool(true)
string(1) "x"
string(1) "x"
int(5)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
string(1) "y"
string(1) "y"
array(0) {
}
array(0) {
}
array_fill(): Argument #2 ($count) must be greater than or equal to 0

--TEST--
Bigint: array literals accept a bigint key
--FILE--
<?php
// A bigint key in an array literal.
$a = [2 ** 100 => 'x', 5 => 'y'];
var_dump($a[2 ** 100]);
var_dump(count($a));
var_dump(array_key_first($a) === 2 ** 100);

// A string decimal key is treated as a bigint and overwrites the previous
// bigint key of the same value.
$b = [2 ** 100 => 'a', '1267650600228229401496703205376' => 'b'];
var_dump(count($b));
var_dump($b[2 ** 100]);

// A bigint key built at runtime.
$k = 2 ** 100;
$c = [$k => 'z'];
var_dump($c[2 ** 100]);
?>
--EXPECT--
string(1) "x"
int(2)
bool(true)
int(1)
string(1) "b"
string(1) "z"

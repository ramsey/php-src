--TEST--
Bigint: array iteration yields an out-of-range integer key as an int
--FILE--
<?php
$a = [];
$a[2 ** 100] = 'x';

// foreach yields the key as an int, not a string.
foreach ($a as $k => $v) {
    var_dump(is_int($k));
    var_dump($k === 2 ** 100);
}

// key(), array_key_first(), array_key_last() all yield an int.
var_dump(key($a) === 2 ** 100);
var_dump(array_key_first($a) === 2 ** 100);
$a[2 ** 101] = 'y';
var_dump(array_key_last($a) === 2 ** 101);

// An object property that looks like an out-of-range decimal stays a string key.
$o = new stdClass();
$o->{'1267650600228229401496703205376'} = 1;
foreach ($o as $k => $v) {
    var_dump(is_string($k));
    var_dump($k);
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
string(31) "1267650600228229401496703205376"

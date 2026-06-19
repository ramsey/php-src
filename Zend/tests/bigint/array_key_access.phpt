--TEST--
Bigint: arrays accept a bigint key for write, read, isset, and unset
--FILE--
<?php
$a = [];
$a[2 ** 100] = 'x';

// Read back by the same bigint expression.
var_dump($a[2 ** 100]);
var_dump(count($a));

// isset with a bigint offset.
var_dump(isset($a[2 ** 100]));
var_dump(isset($a[2 ** 99]));

// A different bigint key is a different slot.
$a[2 ** 101] = 'y';
var_dump(count($a));
var_dump($a[2 ** 101]);

// The canonical decimal string collapses onto the same bucket as the bigint.
var_dump($a['1267650600228229401496703205376']);
$a['1267650600228229401496703205376'] = 'z';
var_dump($a[2 ** 100]);
var_dump(count($a));

// A non-canonical decimal string (leading zero) stays a distinct string key.
$a['01267650600228229401496703205376'] = 'w';
var_dump(count($a));
var_dump(isset($a[2 ** 100]));
var_dump(isset($a['01267650600228229401496703205376']));
var_dump($a[2 ** 100]);
var_dump($a['01267650600228229401496703205376']);

// unset by bigint offset removes the slot.
unset($a[2 ** 100]);
var_dump(isset($a[2 ** 100]));
var_dump(isset($a['1267650600228229401496703205376']));
var_dump(count($a));
?>
--EXPECT--
string(1) "x"
int(1)
bool(true)
bool(false)
int(2)
string(1) "y"
string(1) "x"
string(1) "z"
int(2)
int(3)
bool(true)
bool(true)
string(1) "z"
string(1) "w"
bool(false)
bool(false)
int(2)

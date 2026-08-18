--TEST--
bigint: string offsets with a boxed integer: isset/empty saturate out of range, a direct read reports the offset
--FILE--
<?php
$pos = 340282366920938463463374607431768211456;
$neg = -340282366920938463463374607431768211456;
$s = 'abc';

var_dump(isset($s[$pos]));
var_dump(isset($s[$neg]));
var_dump(empty($s[$pos]));
var_dump(empty($s[$neg]));

var_dump($s[$pos]);
var_dump($s[$pos] ?? 'none');

var_dump($s[$neg]);
var_dump($s[$neg] ?? 'none');
?>
--EXPECTF--
bool(false)
bool(false)
bool(true)
bool(true)

Warning: Uninitialized string offset 340282366920938463463374607431768211456 in %s on line %d
string(0) ""
string(4) "none"

Warning: Uninitialized string offset -340282366920938463463374607431768211456 in %s on line %d
string(0) ""
string(4) "none"

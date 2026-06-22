--TEST--
Bigint: array_key_exists() accepts a bigint key, consistent with isset()
--INI--
opcache.enable_cli=0
--FILE--
<?php

$a = [];
$a[2 ** 70] = 'big';
$a[5] = 'small';
$a[-(2 ** 70)] = 'neg';

var_dump(array_key_exists(2 ** 70, $a));
var_dump(array_key_exists(2 ** 71, $a));
var_dump(array_key_exists(-(2 ** 70), $a));
var_dump(array_key_exists(5, $a));

// Consistent with isset() on the same keys.
var_dump(isset($a[2 ** 70]));
var_dump(isset($a[2 ** 71]));

// The indirect-call fallback (not the dedicated opcode) handles a bigint key too.
$fn = 'array_key_exists';
var_dump($fn(2 ** 70, $a));
var_dump($fn(2 ** 71, $a));
var_dump($fn(-(2 ** 70), $a));
var_dump(call_user_func('array_key_exists', 2 ** 70, $a));
var_dump(call_user_func('array_key_exists', 2 ** 71, $a));
var_dump(call_user_func('array_key_exists', -(2 ** 70), $a));

?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(false)
bool(true)
bool(true)
bool(false)
bool(true)

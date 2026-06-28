--TEST--
Bigint: an out-of-range string offset diagnostic reports the value written, not the clamped bound
--INI--
opcache.enable_cli=0
--FILE--
<?php

$s = 'foo';

// Read: the warning reports the integer passed, not the clamped bound.
var_dump($s[111111111111111111111]);
var_dump($s[-111111111111111111111]);

// Write: a negative out-of-range offset reports the integer passed.
$s[-111111111111111111111] = 'x';

// Beyond zend.int_string_max_digits, the value is shown as a placeholder.
var_dump($s[10 ** 5000]);
$s[-(10 ** 5000)] = 'x';

?>
--EXPECTF--
Warning: Uninitialized string offset 111111111111111111111 in %s on line %d
string(0) ""

Warning: Uninitialized string offset -111111111111111111111 in %s on line %d
string(0) ""

Warning: Illegal string offset -111111111111111111111 in %s on line %d

Warning: Uninitialized string offset <integer too large to display> in %s on line %d
string(0) ""

Warning: Illegal string offset <integer too large to display> in %s on line %d

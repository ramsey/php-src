--TEST--
abs() with bigint operands
--EXTENSIONS--
zend_test
--FILE--
<?php

// Negative bigint negates at full precision
var_dump(abs(-(2 ** 100)) === 2 ** 100);

// Positive bigint identity: same value and the original stays intact
$pos = 2 ** 100;
var_dump(abs($pos) === 2 ** 100);
var_dump($pos === 2 ** 100);

// Longs and floats are unchanged
var_dump(abs(-5));
var_dump(abs(5));
var_dump(abs(-1.5));

// In-range non-canonical bigint: negation canonicalizes to IS_LONG
var_dump(abs(zend_test_make_bigint('-5')) === 5);

// Positive in-range non-canonical bigint passes through by value;
// the tag stays bigint internally
var_dump(abs(zend_test_make_bigint('5')));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
int(5)
int(5)
float(1.5)
bool(true)
int(5)

--TEST--
mb_strimwidth(): big-integer $start/$width hit the function's own range checks, not the int range
--EXTENSIONS--
mbstring
zend_test
--FILE--
<?php
$s = 'abcdef';

// In-range bigint arguments behave like the equivalent int.
var_dump(mb_strimwidth($s, zend_test_make_bigint('0'), zend_test_make_bigint('3'), '', 'UTF-8'));

// A big-integer $start of either sign is out of range.
try {
    mb_strimwidth($s, 2 ** 100, 10, '', 'UTF-8');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
try {
    mb_strimwidth($s, -(2 ** 100), 10, '', 'UTF-8');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A positive big-integer $width keeps the whole string.
var_dump(mb_strimwidth($s, 0, 2 ** 100, '', 'UTF-8'));

// A negative big-integer $width is deprecated and out of range.
try {
    mb_strimwidth($s, 0, -(2 ** 100), '', 'UTF-8');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECTF--
string(3) "abc"
mb_strimwidth(): Argument #2 ($start) is out of range
mb_strimwidth(): Argument #2 ($start) is out of range
string(6) "abcdef"

Deprecated: mb_strimwidth(): passing a negative integer to argument #3 ($width) is deprecated in %s on line %d
mb_strimwidth(): Argument #3 ($width) is out of range

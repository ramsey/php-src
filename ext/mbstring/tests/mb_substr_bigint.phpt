--TEST--
mb_substr(): big-integer $from/$length fall outside the string, not the int range
--EXTENSIONS--
mbstring
zend_test
--FILE--
<?php
$s = 'abcdef';

// In-range bigint arguments behave like the equivalent int.
var_dump(mb_substr($s, zend_test_make_bigint('2'), null, 'UTF-8'));
var_dump(mb_substr($s, zend_test_make_bigint('1'), zend_test_make_bigint('3'), 'UTF-8'));

// A positive big-integer $from is past the end: empty result.
var_dump(mb_substr($s, 2 ** 100, null, 'UTF-8'));

// A negative big-integer $from is out of the supported range.
try {
    mb_substr($s, -(2 ** 100), null, 'UTF-8');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A positive big-integer $length keeps the whole remainder.
var_dump(mb_substr($s, 1, 2 ** 100, 'UTF-8'));

// A negative big-integer $length is out of the supported range.
try {
    mb_substr($s, 1, -(2 ** 100), 'UTF-8');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
string(4) "cdef"
string(3) "bcd"
string(0) ""
mb_substr(): Argument #2 ($start) must be between -9223372036854775807 and 9223372036854775807
string(5) "bcdef"
mb_substr(): Argument #3 ($length) must be between -9223372036854775807 and 9223372036854775807

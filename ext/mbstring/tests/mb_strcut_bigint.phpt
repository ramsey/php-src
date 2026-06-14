--TEST--
mb_strcut(): big-integer $start/$length clamp like a huge int, not the int range
--EXTENSIONS--
mbstring
zend_test
--FILE--
<?php
$s = 'abcdef';

// In-range bigint arguments behave like the equivalent int.
var_dump(mb_strcut($s, zend_test_make_bigint('2'), null, 'UTF-8'));
var_dump(mb_strcut($s, zend_test_make_bigint('1'), zend_test_make_bigint('3'), 'UTF-8'));

// Out-of-range big integers clamp the same way a huge int of that sign does.
var_dump(mb_strcut($s, 2 ** 100, null, 'UTF-8'));
var_dump(mb_strcut($s, -(2 ** 100), null, 'UTF-8'));
var_dump(mb_strcut($s, 1, 2 ** 100, 'UTF-8'));
var_dump(mb_strcut($s, 1, -(2 ** 100), 'UTF-8'));
?>
--EXPECT--
string(4) "cdef"
string(3) "bcd"
string(0) ""
string(6) "abcdef"
string(5) "bcdef"
string(0) ""

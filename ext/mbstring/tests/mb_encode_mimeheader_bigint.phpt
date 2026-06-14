--TEST--
mb_encode_mimeheader(): an out-of-range big-integer $indent clamps to 0, not the int range
--EXTENSIONS--
mbstring
zend_test
--FILE--
<?php
$s = 'Aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';

// An in-range bigint $indent matches the equivalent int.
var_dump(
    mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n", zend_test_make_bigint('10'))
    === mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n", 10)
);

// Out-of-range big integers clamp $indent to 0 (its own out-of-range behavior).
var_dump(
    mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n", 2 ** 100)
    === mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n", 0)
);
var_dump(
    mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n", -(2 ** 100))
    === mb_encode_mimeheader($s, 'UTF-8', 'B', "\r\n", 0)
);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)

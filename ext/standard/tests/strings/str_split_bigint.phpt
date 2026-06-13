--TEST--
str_split(): a big-integer $length clamps to one chunk or is rejected like an out-of-range long
--EXTENSIONS--
zend_test
--FILE--
<?php
// Positive big integer: one chunk with the whole string.
var_dump(str_split('abcdef', 2 ** 100));

// Empty string with a big-integer length.
var_dump(str_split('', 2 ** 100));

// In-range bigint length.
var_dump(str_split('abcdef', zend_test_make_bigint('2')));

// Non-positive length, including a big integer.
try {
    str_split('abc', -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
array(1) {
  [0]=>
  string(6) "abcdef"
}
array(0) {
}
array(3) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(2) "cd"
  [2]=>
  string(2) "ef"
}
str_split(): Argument #2 ($length) must be greater than 0

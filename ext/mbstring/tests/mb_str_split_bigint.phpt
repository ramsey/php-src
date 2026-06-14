--TEST--
mb_str_split(): a big-integer $length is rejected by its own size limit, not the int range
--EXTENSIONS--
mbstring
zend_test
--FILE--
<?php
var_dump(mb_str_split('abcdef', 2, 'UTF-8'));
var_dump(mb_str_split('abcdef', zend_test_make_bigint('2'), 'UTF-8'));

// A positive big integer exceeds the chunk-size limit.
try {
    mb_str_split('abc', 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A negative big integer is not a positive length.
try {
    mb_str_split('abc', -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
array(3) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(2) "cd"
  [2]=>
  string(2) "ef"
}
array(3) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(2) "cd"
  [2]=>
  string(2) "ef"
}
mb_str_split(): Argument #2 ($length) is too large
mb_str_split(): Argument #2 ($length) must be greater than 0

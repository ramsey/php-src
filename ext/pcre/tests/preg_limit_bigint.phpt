--TEST--
preg_*(): a big-integer $limit behaves like an out-of-range long limit (unlimited)
--EXTENSIONS--
pcre
zend_test
--FILE--
<?php
// preg_replace: a positive big integer is an effectively unlimited cap.
var_dump(preg_replace('/a/', 'X', 'aaa', 2 ** 100));
// A negative big integer is also unlimited (like the -1 default).
var_dump(preg_replace('/a/', 'X', 'aaa', -(2 ** 100)));
// An in-range bigint limit caps as usual.
var_dump(preg_replace('/a/', 'X', 'aaa', zend_test_make_bigint('2')));

var_dump(preg_replace_callback('/a/', fn($m) => 'X', 'aaa', 2 ** 100));
var_dump(preg_replace_callback_array(['/a/' => fn($m) => 'X'], 'aaa', 2 ** 100));
var_dump(preg_split('/,/', 'a,b,c', 2 ** 100));
?>
--EXPECT--
string(3) "XXX"
string(3) "XXX"
string(3) "XXa"
string(3) "XXX"
string(3) "XXX"
array(3) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
}

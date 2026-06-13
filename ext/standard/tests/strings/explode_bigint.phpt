--TEST--
explode(): a big-integer $limit behaves like an out-of-range long limit
--EXTENSIONS--
zend_test
--FILE--
<?php
// Positive big integer: no effective limit, all pieces.
var_dump(explode(',', 'a,b,c,d', 2 ** 100));

// Negative big integer: removes more pieces than exist, empty result.
var_dump(explode(',', 'a,b,c,d', -(2 ** 100)));

// In-range bigint limit.
var_dump(explode(',', 'a,b,c,d', zend_test_make_bigint('2')));
?>
--EXPECT--
array(4) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
  [3]=>
  string(1) "d"
}
array(0) {
}
array(2) {
  [0]=>
  string(1) "a"
  [1]=>
  string(5) "b,c,d"
}

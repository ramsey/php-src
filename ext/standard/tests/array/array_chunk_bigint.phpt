--TEST--
array_chunk(): a big-integer $length clamps or is rejected like an out-of-range long
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = [1, 2, 3];

// Positive big integer: one chunk with everything.
var_dump(array_chunk($a, 2 ** 100));

// Empty input with a big-integer length.
var_dump(array_chunk([], 2 ** 100));

// In-range bigint length.
var_dump(array_chunk($a, zend_test_make_bigint('2')));

// Non-positive length, including a big integer.
try {
    array_chunk($a, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
array(1) {
  [0]=>
  array(3) {
    [0]=>
    int(1)
    [1]=>
    int(2)
    [2]=>
    int(3)
  }
}
array(0) {
}
array(2) {
  [0]=>
  array(2) {
    [0]=>
    int(1)
    [1]=>
    int(2)
  }
  [1]=>
  array(1) {
    [0]=>
    int(3)
  }
}
array_chunk(): Argument #2 ($length) must be greater than 0

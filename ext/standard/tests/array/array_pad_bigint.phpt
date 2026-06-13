--TEST--
array_pad(): a logical-int $length accepts big integers and enforces the array-size limit
--EXTENSIONS--
zend_test
--FILE--
<?php
$a = [1, 2];

// Normal in-range lengths still pad on the right (positive) and left (negative).
var_dump(array_pad($a, 4, 0));
var_dump(array_pad($a, -4, 0));

// A non-canonical in-range bigint $length is read as the equivalent int and pads normally.
var_dump(array_pad($a, zend_test_make_bigint('4'), 0));

// A length over the maximum array size is rejected (existing behavior, value still fits a long).
try {
    array_pad($a, PHP_INT_MAX, 0);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A big integer is always beyond the array-size limit, so it hits the same error rather than
// the generic integer-range error, never revealing that an int has a maximum. Both signs behave alike.
try {
    array_pad($a, 2 ** 100, 0);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

try {
    array_pad($a, -(2 ** 100), 0);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(0)
  [3]=>
  int(0)
}
array(4) {
  [0]=>
  int(0)
  [1]=>
  int(0)
  [2]=>
  int(1)
  [3]=>
  int(2)
}
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(0)
  [3]=>
  int(0)
}
array_pad(): Argument #2 ($length) must not exceed the maximum allowed array size
array_pad(): Argument #2 ($length) must not exceed the maximum allowed array size
array_pad(): Argument #2 ($length) must not exceed the maximum allowed array size

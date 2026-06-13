--TEST--
array_fill(): a big-integer $count is rejected by its own size limit, not the int range
--EXTENSIONS--
zend_test
--FILE--
<?php
// In-range bigint count fills normally.
var_dump(array_fill(0, zend_test_make_bigint('3'), 'x'));

// A positive big integer can never fit an array.
try {
    array_fill(0, 2 ** 100, 'x');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A negative big integer is below the minimum.
try {
    array_fill(0, -(2 ** 100), 'x');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
array(3) {
  [0]=>
  string(1) "x"
  [1]=>
  string(1) "x"
  [2]=>
  string(1) "x"
}
array_fill(): Argument #2 ($count) is too large
array_fill(): Argument #2 ($count) must be greater than or equal to 0

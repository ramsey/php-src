--TEST--
unpack(): a big-integer $offset is rejected by the data-bounds check, not the int range
--EXTENSIONS--
zend_test
--FILE--
<?php
$data = pack('N', 305419896);

// In-range bigint offset.
var_dump(unpack('N', $data, zend_test_make_bigint('0')));

// A big integer can't lie within the data (both signs).
try {
    unpack('N', $data, 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
try {
    unpack('N', $data, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
array(1) {
  [1]=>
  int(305419896)
}
unpack(): Argument #3 ($offset) must be contained in argument #2 ($data)
unpack(): Argument #3 ($offset) must be contained in argument #2 ($data)

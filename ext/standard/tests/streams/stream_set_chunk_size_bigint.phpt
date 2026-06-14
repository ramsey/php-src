--TEST--
stream_set_chunk_size(): big-integer $size behaves like an out-of-range long
--EXTENSIONS--
zend_test
--FILE--
<?php
$stream = fopen('php://memory', 'r+');

// An in-range bigint size behaves like the same long.
var_dump(stream_set_chunk_size($stream, zend_test_make_bigint('8192')));

// A huge positive size is rejected as too large, like a huge long.
try {
    stream_set_chunk_size($stream, 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A negative bigint size is rejected like a negative long.
try {
    stream_set_chunk_size($stream, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

fclose($stream);
?>
--EXPECT--
int(8192)
stream_set_chunk_size(): Argument #2 ($size) is too large
stream_set_chunk_size(): Argument #2 ($size) must be greater than 0

--TEST--
stream_get_line(): big-integer $length behaves like an out-of-range long
--EXTENSIONS--
zend_test
--FILE--
<?php
$stream = fopen('php://memory', 'r+');
fwrite($stream, "first line\nsecond line\n");
rewind($stream);

// In-range bigint length behaves like the same long.
var_dump(stream_get_line($stream, zend_test_make_bigint('100'), "\n"));

// A huge positive length caps past the data, reading the whole record, like a huge long.
rewind($stream);
var_dump(stream_get_line($stream, 2 ** 100, "\n"));

// A negative bigint length is rejected like a negative long.
try {
    stream_get_line($stream, -(2 ** 100), "\n");
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

fclose($stream);
?>
--EXPECT--
string(10) "first line"
string(10) "first line"
stream_get_line(): Argument #2 ($length) must be greater than or equal to 0

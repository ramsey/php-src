--TEST--
stream_get_contents(): big-integer $length and $offset behave like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
$stream = fopen('php://memory', 'r+');
fwrite($stream, 'hello world');
rewind($stream);

// An in-range bigint length behaves like the same long.
var_dump(stream_get_contents($stream, zend_test_make_bigint('5')));

// A huge positive length caps at the available data, like a huge long.
rewind($stream);
var_dump(stream_get_contents($stream, 2 ** 100));

// A negative bigint length is rejected like a negative long.
try {
    stream_get_contents($stream, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A huge positive offset seeks past the end, reading nothing, like a huge long.
var_dump(stream_get_contents($stream, null, 2 ** 100));

// An in-range bigint offset behaves like the same long.
var_dump(stream_get_contents($stream, null, zend_test_make_bigint('6')));

fclose($stream);
?>
--EXPECT--
string(5) "hello"
string(11) "hello world"
stream_get_contents(): Argument #2 ($length) must be greater than or equal to -1
string(0) ""
string(5) "world"

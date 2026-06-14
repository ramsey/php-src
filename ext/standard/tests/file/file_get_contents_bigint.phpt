--TEST--
file_get_contents(): big-integer $offset and $length behave like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
$file = __DIR__ . '/file_get_contents_bigint.txt';
file_put_contents($file, 'hello world');

// In-range bigint length behaves like the same long.
var_dump(file_get_contents($file, false, null, 0, zend_test_make_bigint('5')));

// Positive bigint length caps at the file size, like a huge long.
var_dump(file_get_contents($file, false, null, 0, 2 ** 100));

// Negative bigint length is rejected like a negative long.
try {
    file_get_contents($file, false, null, 0, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// Positive bigint offset seeks past the end, reading nothing, like a huge long.
var_dump(file_get_contents($file, false, null, 2 ** 100));

// In-range bigint offset behaves like the same long.
var_dump(file_get_contents($file, false, null, zend_test_make_bigint('6')));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/file_get_contents_bigint.txt');
?>
--EXPECT--
string(5) "hello"
string(11) "hello world"
file_get_contents(): Argument #5 ($length) must be greater than or equal to 0
string(0) ""
string(5) "world"

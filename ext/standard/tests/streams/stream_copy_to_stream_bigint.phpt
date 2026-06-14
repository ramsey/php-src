--TEST--
stream_copy_to_stream(): big-integer $length and $offset behave like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
function make_src() {
    $src = fopen('php://memory', 'r+');
    fwrite($src, 'hello world');
    rewind($src);
    return $src;
}

// An in-range bigint length copies that many bytes, like the same long.
$src = make_src();
$dest = fopen('php://memory', 'r+');
var_dump(stream_copy_to_stream($src, $dest, zend_test_make_bigint('5')));

// A huge positive length copies everything available, like a huge long.
$src = make_src();
$dest = fopen('php://memory', 'r+');
var_dump(stream_copy_to_stream($src, $dest, 2 ** 100));

// A huge positive offset seeks past the end, copying nothing, like a huge long.
$src = make_src();
$dest = fopen('php://memory', 'r+');
var_dump(stream_copy_to_stream($src, $dest, null, 2 ** 100));

// An in-range bigint offset starts the copy there, like the same long.
$src = make_src();
$dest = fopen('php://memory', 'r+');
var_dump(stream_copy_to_stream($src, $dest, null, zend_test_make_bigint('6')));
?>
--EXPECT--
int(5)
int(11)
int(0)
int(5)

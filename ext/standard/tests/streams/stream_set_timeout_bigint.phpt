--TEST--
stream_set_timeout(): big-integer $seconds and $microseconds behave like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
$stream = stream_socket_client('udp://127.0.0.1:9', $errno, $errstr);

// An in-range bigint timeout is accepted, like the same long.
var_dump(stream_set_timeout($stream, zend_test_make_bigint('5')));

// A huge positive timeout is accepted just like a huge long.
var_dump(stream_set_timeout($stream, 2 ** 100));

// A huge negative timeout is accepted just like a huge negative long.
var_dump(stream_set_timeout($stream, -(2 ** 100)));

// A big-integer microseconds component is accepted too.
var_dump(stream_set_timeout($stream, 1, 2 ** 100));

fclose($stream);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)

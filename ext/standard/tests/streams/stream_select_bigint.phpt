--TEST--
stream_select(): big-integer $seconds and $microseconds behave like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
[$a, $b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
fwrite($a, 'x');

// An in-range bigint timeout polls and reports the readable stream, like the same long.
$r = [$b];
$w = $e = [];
var_dump(stream_select($r, $w, $e, zend_test_make_bigint('0')));

// A negative bigint $seconds is rejected like a negative long.
$r = [$b];
$w = $e = [];
try {
    stream_select($r, $w, $e, -(2 ** 100));
} catch (ValueError $ex) {
    echo $ex->getMessage() . "\n";
}

// A negative bigint $microseconds is rejected like a negative long.
$r = [$b];
$w = $e = [];
try {
    stream_select($r, $w, $e, 0, -(2 ** 100));
} catch (ValueError $ex) {
    echo $ex->getMessage() . "\n";
}

fclose($a);
fclose($b);
?>
--EXPECT--
int(1)
stream_select(): Argument #4 ($seconds) must be greater than or equal to 0
stream_select(): Argument #5 ($microseconds) must be greater than or equal to 0

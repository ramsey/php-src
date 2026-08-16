--TEST--
Non rep float to int conversions should not crash when modified
--FILE--
<?php

set_error_handler(function ($errno, $errstr) {
    global $ary;
    $ary = null;
    echo $errstr, "\n";
});

$ary = [rand()];
var_dump(\array_key_exists(INF, $ary));

?>
--EXPECT--
The float INF is not representable as an int, cast occurred
bool(false)

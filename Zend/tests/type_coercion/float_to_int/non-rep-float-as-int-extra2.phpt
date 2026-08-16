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
unset($ary[INF]);

?>
--EXPECT--
The float INF is not representable as an int, cast occurred

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
var_dump(isset($ary[NAN]));

?>
--EXPECT--
The float NAN is not representable as an int, cast occurred
Implicit conversion from float NAN to int loses precision
bool(false)

--TEST--
Capturing array in user error handler during index conversion
--FILE--
<?php
set_error_handler(function($code, $msg) {
    echo "Err: $msg\n";
    $GLOBALS[''] = $GLOBALS['y'];
});
function x(&$s){
    $s[2.5] = 1;
}
x($y);
var_dump($y);
?>
--EXPECT--
Err: Implicit conversion from float 2.5 to int loses precision
array(0) {
}

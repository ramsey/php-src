--TEST--
bigint: a big integer literal array offset raises a type error without corrupting the array
--FILE--
<?php
$a = [];
try {
    $a[-340282366920938463463374607431768211456] = 1;
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
var_dump($a);
?>
--EXPECT--
TypeError: Cannot access offset of type int on array
array(0) {
}

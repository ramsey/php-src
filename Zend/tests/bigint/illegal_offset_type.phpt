--TEST--
bigint: an illegal array offset raises a type error without corrupting the array
--FILE--
<?php
$a = [];
try {
    $a[[]] = 1;
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
var_dump($a);
?>
--EXPECT--
TypeError: Cannot access offset of type array on array
array(0) {
}

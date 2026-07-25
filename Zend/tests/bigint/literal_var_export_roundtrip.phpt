--TEST--
bigint: var_export of a big integer round-trips through eval as an exact int
--FILE--
<?php
$big = 340282366920938463463374607431768211456;
$exported = var_export($big, true);
echo $exported . "\n";
var_dump(eval('return ' . $exported . ';') === $big);

var_dump(eval('return ' . var_export(PHP_INT_MIN, true) . ';') === PHP_INT_MIN);
?>
--EXPECT--
340282366920938463463374607431768211456
bool(true)
bool(true)

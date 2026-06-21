--TEST--
array_product() packed long overflow promotes to a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php

$tests = [
    [[PHP_INT_MAX, 2, 3], PHP_INT_MAX * 2 * 3],
    [[PHP_INT_MIN, -1, 2], PHP_INT_MIN * -1 * 2],
];

foreach ($tests as [$input, $expected]) {
    $result = array_product($input);
    var_dump(is_int($result));
    var_dump($result === $expected);
}

// A product that overflows and then returns to long range normalizes back to a plain int.
var_dump(array_product([PHP_INT_MAX, 2, 0]) === 0);

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

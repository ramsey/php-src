--TEST--
array_sum() packed long overflow promotes to a bigint
--INI--
opcache.enable_cli=0
--FILE--
<?php

$tests = [
    [[PHP_INT_MAX, 1, 4096], PHP_INT_MAX + 1 + 4096],
    [[PHP_INT_MIN, -1, -4096], PHP_INT_MIN - 1 - 4096],
];

foreach ($tests as [$input, $expected]) {
    $result = array_sum($input);
    var_dump(is_int($result));
    var_dump($result === $expected);
}

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)

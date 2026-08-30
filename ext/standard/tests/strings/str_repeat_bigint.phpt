--TEST--
str_repeat() accepts logical int $times
--FILE--
<?php
var_dump(str_repeat('ab', 2));
try {
    str_repeat('a', -(10 ** 30));
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    str_repeat('a', -1);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
var_dump(str_repeat('', 10 ** 30));
?>
--EXPECT--
string(4) "abab"
ValueError: str_repeat(): Argument #2 ($times) must be greater than or equal to 0
ValueError: str_repeat(): Argument #2 ($times) must be greater than or equal to 0
string(0) ""

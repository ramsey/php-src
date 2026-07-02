--TEST--
Bigint: allocation-sizing builtins throw a catchable MemoryError instead of an uncatchable fatal
--INI--
memory_limit=64M
opcache.enable_cli=0
--FILE--
<?php

$e = new MemoryError();
var_dump($e instanceof Error);

foreach (['bigint' => 2 ** 70, 'long' => PHP_INT_MAX] as $label => $n) {
    try {
        str_repeat('ab', $n);
    } catch (MemoryError $e) {
        echo "str_repeat ($label): " . get_class($e) . ': ' . $e->getMessage() . "\n";
    }
    try {
        str_pad('x', $n);
    } catch (MemoryError $e) {
        echo "str_pad ($label): " . get_class($e) . "\n";
    }
}

try {
    mb_str_pad('x', 2 ** 70);
} catch (MemoryError $e) {
    echo 'mb_str_pad: ' . get_class($e) . "\n";
}

try {
    random_bytes(2 ** 70);
} catch (MemoryError $e) {
    echo 'random_bytes (bigint): ' . get_class($e) . "\n";
}
try {
    random_bytes(PHP_INT_MAX);
} catch (MemoryError $e) {
    echo 'random_bytes (long): ' . get_class($e) . "\n";
}

try {
    str_repeat('ab', -(2 ** 70));
} catch (ValueError $e) {
    echo 'str_repeat neg: ' . $e->getMessage() . "\n";
}

?>
--EXPECT--
bool(true)
str_repeat (bigint): MemoryError: The resulting string is too large to fit in the configured memory limit
str_pad (bigint): MemoryError
str_repeat (long): MemoryError: The resulting string is too large to fit in the configured memory limit
str_pad (long): MemoryError
mb_str_pad: MemoryError
random_bytes (bigint): MemoryError
random_bytes (long): MemoryError
str_repeat neg: str_repeat(): Argument #2 ($times) must be greater than or equal to 0

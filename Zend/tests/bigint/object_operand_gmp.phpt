--TEST--
bigint: gmp operand opposite a box fails closed
--EXTENSIONS--
gmp
--FILE--
<?php
$big = 2 ** 100;

try {
    var_dump(gmp_init(2) % $big);
} catch (Error $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    var_dump($big % gmp_init(2));
} catch (Error $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
ValueError: main(): Argument #2 must be between -9223372036854775808 and 9223372036854775807
ValueError: main(): Argument #1 must be between -9223372036854775808 and 9223372036854775807

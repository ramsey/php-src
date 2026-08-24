--TEST--
bigint: gmp operand opposite a box fails closed
--EXTENSIONS--
gmp
--FILE--
<?php
function check(Error $e, int $argNum): void {
    $expected = 'main(): Argument #' . $argNum . ' must be between ' . PHP_INT_MIN . ' and ' . PHP_INT_MAX;
    if ($e->getMessage() === $expected) {
        echo $e::class . ': argument #' . $argNum . ' rejected with the platform int bounds' . "\n";
    } else {
        echo $e::class . ': ' . $e->getMessage() . "\n";
    }
}

$big = 2 ** 100;

try {
    var_dump(gmp_init(2) % $big);
} catch (Error $e) {
    check($e, 2);
}

try {
    var_dump($big % gmp_init(2));
} catch (Error $e) {
    check($e, 1);
}
?>
--EXPECT--
ValueError: argument #2 rejected with the platform int bounds
ValueError: argument #1 rejected with the platform int bounds

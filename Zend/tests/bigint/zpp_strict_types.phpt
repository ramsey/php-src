--TEST--
bigint: strict_types widens a boxed int to float exactly; int parameters, including a frameless one, still throw the range ValueError
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);

$big = zend_test_bigint_make('340282366920938463463374607431768211456');

var_dump(sqrt($big) === sqrt(2.0 ** 128));

try {
    str_repeat('a', $big);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    dechex($big);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
bool(true)
ValueError: str_repeat(): Argument #2 ($times) must be between %i and %i
ValueError: dechex(): Argument #1 ($num) must be between %i and %i

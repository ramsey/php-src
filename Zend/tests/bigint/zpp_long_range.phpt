--TEST--
Bigint: Z_PARAM_LONG converts in-range bigints and throws ValueError for out-of-range (weak mode)
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--EXTENSIONS--
zend_test
--FILE--
<?php
// Out-of-range positive bigint -> caught ValueError; message must name the long bounds.
try {
    dechex(2 ** 64);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Out-of-range negative bigint -> same ValueError.
try {
    dechex(-2 ** 64);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// In-range non-canonical bigint -> lossless conversion.
$b = zend_test_make_bigint('255');
var_dump(dechex($b));

// Z_PARAM_STR_OR_LONG consumer (array_column third arg) receiving an out-of-range
// bigint -> same ValueError via the shared slow path.
try {
    array_column([['k' => 'v']], 'k', 2 ** 64);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Old-style "l"-format consumer (ArrayObject::setFlags) receiving an out-of-range
// bigint -> same ValueError via zend_parse_arg / zend_parse_arg_long_slow.
$ao = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
try {
    $ao->setFlags(2 ** 64);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Z_PARAM_STR_OR_LONG success: array_column with a fitting bigint as the third
// (column-key) argument must behave identically to the same literal integer.
$rows = [['a' => 'x', 'b' => 'y'], ['a' => 'p', 'b' => 'q']];
$expected = array_column($rows, null, 0);
$actual   = array_column($rows, null, zend_test_make_bigint('0'));
var_dump($expected === $actual);

// Exact boundary cases.
var_dump(dechex(zend_test_make_bigint('9223372036854775807')));
var_dump(dechex(zend_test_make_bigint('-9223372036854775808')));
try {
    dechex(zend_test_make_bigint('9223372036854775808'));
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
ValueError: dechex(): Argument #1 ($num) must be between -9223372036854775808 and 9223372036854775807
ValueError: dechex(): Argument #1 ($num) must be between -9223372036854775808 and 9223372036854775807
string(2) "ff"
ValueError: array_column(): Argument #3 ($index_key) must be between -9223372036854775808 and 9223372036854775807
ValueError: ArrayObject::setFlags(): Argument #1 ($flags) must be between -9223372036854775808 and 9223372036854775807
bool(true)
string(16) "7fffffffffffffff"
string(16) "8000000000000000"
ValueError: dechex(): Argument #1 ($num) must be between -9223372036854775808 and 9223372036854775807

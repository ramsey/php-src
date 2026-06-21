--TEST--
Bigint: Z_PARAM_LONG converts in-range bigints and throws ValueError for out-of-range (weak mode)
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--EXTENSIONS--
zend_test
--FILE--
<?php
// Out-of-range positive bigint -> caught ValueError; message must name the long bounds.
try {
    chr(2 ** 32);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Out-of-range negative bigint -> same ValueError.
try {
    chr(-2 ** 32);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// In-range non-canonical bigint -> lossless conversion.
$b = zend_test_make_bigint('65');
var_dump(chr($b));

// Z_PARAM_STR_OR_LONG consumer (array_column third arg) receiving an out-of-range
// bigint -> same ValueError via the shared slow path.
try {
    array_column([['k' => 'v']], 'k', 2 ** 32);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Old-style "l"-format consumer (ArrayObject::setFlags) receiving an out-of-range
// bigint -> same ValueError via zend_parse_arg / zend_parse_arg_long_slow.
$ao = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
try {
    $ao->setFlags(2 ** 32);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Z_PARAM_STR_OR_LONG success: array_column with a fitting bigint as the third
// (column-key) argument must behave identically to the same literal integer.
$rows = [['a' => 'x', 'b' => 'y'], ['a' => 'p', 'b' => 'q']];
$expected = array_column($rows, null, 0);
$actual   = array_column($rows, null, zend_test_make_bigint('0'));
var_dump($expected === $actual);
?>
--EXPECT--
ValueError: chr(): Argument #1 ($codepoint) must be between -2147483648 and 2147483647
ValueError: chr(): Argument #1 ($codepoint) must be between -2147483648 and 2147483647
string(1) "A"
ValueError: array_column(): Argument #3 ($index_key) must be between -2147483648 and 2147483647
ValueError: ArrayObject::setFlags(): Argument #1 ($flags) must be between -2147483648 and 2147483647
bool(true)

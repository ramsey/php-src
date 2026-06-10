--TEST--
Bigint: Z_PARAM_LONG via the frameless-function (FLF) slow path converts in-range and throws ValueError for out-of-range
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--EXTENSIONS--
zend_test
--FILE--
<?php
// dechex() has a @frameless-function {"arity": 1} annotation; calling it with a
// literal positional argument exercises the ZEND_FRAMELESS_FUNCTION(dechex, 1)
// opcode path which uses Z_FLF_PARAM_LONG -> zend_flf_parse_arg_long_slow.

// Out-of-range bigint via FLF -> ValueError; message names long bounds.
try {
    dechex(2 ** 32);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// In-range non-canonical bigint via FLF -> lossless conversion.
$b = zend_test_make_bigint('255');
var_dump(dechex($b));
?>
--EXPECT--
ValueError: dechex(): Argument #1 ($num) must be between -2147483648 and 2147483647
string(2) "ff"

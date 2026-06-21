--TEST--
Bigint: Z_PARAM_LONG in strict mode accepts in-range bigints and throws ValueError for out-of-range
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);

// In-range bigint in strict mode -> accepted (lossless conversion).
$b = zend_test_make_bigint('65');
var_dump(chr($b));

// Out-of-range bigint in strict mode -> ValueError.
try {
    chr(2 ** 64);
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Non-frameless strict acceptance: ArrayObject::setFlags uses the "l"-format ZPP
// slow path (zend_parse_arg_long_slow); a fitting bigint must succeed.
$ao = new ArrayObject([]);
$ao->setFlags(zend_test_make_bigint('2'));
var_dump($ao->getFlags());
?>
--EXPECT--
string(1) "A"
ValueError: chr(): Argument #1 ($codepoint) must be between -9223372036854775808 and 9223372036854775807
int(2)

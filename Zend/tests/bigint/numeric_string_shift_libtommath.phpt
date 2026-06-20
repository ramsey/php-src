--TEST--
Bigint: huge shift count errors with libtommath backend
--SKIPIF--
<?php if (zend_test_bigint_backend() !== 'libtommath') die('skip libtommath backend only'); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
$s = (string) (PHP_INT_MAX + 1); // out-of-range integer string

// A huge shift count still errors (you cannot shift left by ~2**63 bits)...
try {
    $r = 1 << $s;
} catch (ArithmeticError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
The libtommath bigint backend cannot shift left by more than 2147483647 bits

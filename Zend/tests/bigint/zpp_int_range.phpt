--TEST--
bigint: Z_PARAM_INT_RANGE enforces the declared domain
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(zend_test_zpp_int_range(0));
var_dump(zend_test_zpp_int_range(128));
var_dump(zend_test_zpp_int_range(255));
try {
    zend_test_zpp_int_range(256);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_zpp_int_range(-1);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_zpp_int_range(zend_test_bigint_make('340282366920938463463374607431768211456'));
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
try {
    zend_test_zpp_int_range(1e100);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
var_dump(zend_test_zpp_int_range('128'));
try {
    eval("declare(strict_types=1); zend_test_zpp_int_range(zend_test_bigint_make('340282366920938463463374607431768211456'));");
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(0)
int(128)
int(255)
ValueError: zend_test_zpp_int_range(): Argument #1 ($n) must be between 0 and 255
ValueError: zend_test_zpp_int_range(): Argument #1 ($n) must be between 0 and 255
ValueError: zend_test_zpp_int_range(): Argument #1 ($n) must be between 0 and 255
ValueError: zend_test_zpp_int_range(): Argument #1 ($n) must be between 0 and 255
int(128)
ValueError: zend_test_zpp_int_range(): Argument #1 ($n) must be between 0 and 255

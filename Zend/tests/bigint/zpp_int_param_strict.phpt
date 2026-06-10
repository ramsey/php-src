--TEST--
Bigint: Z_PARAM_INT in strict mode accepts IS_LONG and IS_BIGINT, rejects others
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);

// long passes in strict mode
var_dump(zend_test_zpp_int(5));

// bigint passes at full precision in strict mode
$big = 2 ** 100;
$result = zend_test_zpp_int($big);
var_dump($result === $big);

// string (even numeric) results in a TypeError in strict mode
try {
    zend_test_zpp_int('5');
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

// Integral float results in a TypeError in strict mode
try {
    zend_test_zpp_int(5.0);
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(5)
bool(true)
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, string given
TypeError: zend_test_zpp_int(): Argument #1 ($i) must be of type int, float given

--TEST--
Bigint: old-style 'i' spec char accepts IS_LONG and IS_BIGINT at full precision
--EXTENSIONS--
zend_test
--FILE--
<?php
// long passes through
var_dump(zend_test_zpp_int_oldstyle(5));

// bigint passes at full precision
$big = 2 ** 100;
var_dump(zend_test_zpp_int_oldstyle($big) === $big);

// In-range bigint (via zend_test_make_bigint) stays int
var_dump(zend_test_zpp_int_oldstyle(zend_test_make_bigint('7')));

// Numeric string coerces to int in weak mode
var_dump(zend_test_zpp_int_oldstyle('5'));

// Integral float coerces to int in weak mode
var_dump(zend_test_zpp_int_oldstyle(5.0));

// bool coerces to int
var_dump(zend_test_zpp_int_oldstyle(true));
var_dump(zend_test_zpp_int_oldstyle(false));

// Non-numeric string results in a TypeError
try {
    zend_test_zpp_int_oldstyle('abc');
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
int(5)
bool(true)
int(7)
int(5)
int(5)
int(1)
int(0)
TypeError: zend_test_zpp_int_oldstyle(): Argument #1 ($i) must be of type int, string given

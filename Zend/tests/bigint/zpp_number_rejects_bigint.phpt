--TEST--
Bigint: the deprecated Z_PARAM_NUMBER macro rejects a bigint
--EXTENSIONS--
zend_test
--FILE--
<?php

// zend_number() is a zend_test shim parsing its argument with Z_PARAM_NUMBER.
// That macro is deprecated in favor of the bigint-aware Z_PARAM_INT_OR_FLOAT.
// An int or float still passes through, but a bigint is now rejected with a
// TypeError instead of silently degrading to a (lossy) float.
var_dump(zend_number(5));
var_dump(zend_number(1.5));

try {
    zend_number(2 ** 100);
} catch (\TypeError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
int(5)
float(1.5)
zend_number(): Argument #1 ($param) must be of type int|float, int given

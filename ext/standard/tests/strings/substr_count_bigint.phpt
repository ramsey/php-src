--TEST--
substr_count(): big-integer $offset/$length fall outside the haystack, not the int range
--EXTENSIONS--
zend_test
--FILE--
<?php
$h = 'hello world hello';

// Baseline plus in-range bigint offset and length.
var_dump(substr_count($h, 'hello'));
var_dump(substr_count($h, 'hello', zend_test_make_bigint('6')));
var_dump(substr_count($h, 'hello', 0, zend_test_make_bigint('11')));

// A big-integer offset can't lie within the haystack (both signs).
try {
    substr_count($h, 'hello', 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
try {
    substr_count($h, 'hello', -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A big-integer length can't lie within the haystack (both signs).
try {
    substr_count($h, 'hello', 0, 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
try {
    substr_count($h, 'hello', 0, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
int(2)
int(1)
int(1)
substr_count(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
substr_count(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
substr_count(): Argument #4 ($length) must be contained in argument #1 ($haystack)
substr_count(): Argument #4 ($length) must be contained in argument #1 ($haystack)

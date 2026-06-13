--TEST--
substr_compare(): big-integer $offset/$length behave like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
// Baseline plus an in-range bigint offset.
var_dump(substr_compare('hello', 'lo', 3));
var_dump(substr_compare('hello', 'lo', zend_test_make_bigint('3')));

// A huge negative offset clamps to the start, like a large negative long.
var_dump(substr_compare('hello', 'hello', -(2 ** 100)));

// A huge positive offset is past the end of the haystack.
try {
    substr_compare('hello', 'lo', 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A positive bigint length compares the whole strings.
var_dump(substr_compare('hello', 'hello', 0, 2 ** 100));

// A negative bigint length is rejected.
try {
    substr_compare('hello', 'hello', 0, -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
int(0)
int(0)
int(0)
substr_compare(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
int(0)
substr_compare(): Argument #4 ($length) must be greater than or equal to 0

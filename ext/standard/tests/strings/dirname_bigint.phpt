--TEST--
dirname(): a bigint $levels clamps to the root or is rejected
--EXTENSIONS--
zend_test
--FILE--
<?php
// In-range levels still work.
var_dump(dirname('/foo/bar/baz', 2));

// A positive bigint $levels clamps to the root.
var_dump(dirname('/foo/bar/baz', 2 ** 100));

// A non-canonical in-range bigint $levels is read as the equivalent int.
var_dump(dirname('/foo/bar/baz', zend_test_make_bigint('2')));

// A negative bigint $levels is rejected like any value below 1.
try {
    dirname('/foo/bar/baz', -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
string(4) "/foo"
string(1) "/"
string(4) "/foo"
dirname(): Argument #2 ($levels) must be greater than or equal to 1

--TEST--
strncmp()/strncasecmp(): a big-integer $length compares the whole strings or is rejected
--EXTENSIONS--
zend_test
--FILE--
<?php
// A positive big-integer length compares the full strings.
var_dump(strncmp('abc', 'abc', 2 ** 100) === 0);
var_dump(strncmp('abc', 'abd', 2 ** 100) < 0);

// An in-range bigint length works.
var_dump(strncmp('abc', 'abx', zend_test_make_bigint('2')) === 0);

// A negative big-integer length is rejected.
try {
    strncmp('abc', 'abc', -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// strncasecmp behaves the same.
var_dump(strncasecmp('ABC', 'abc', 2 ** 100) === 0);
try {
    strncasecmp('abc', 'abc', -(2 ** 100));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
strncmp(): Argument #3 ($length) must be greater than or equal to 0
bool(true)
strncasecmp(): Argument #3 ($length) must be greater than or equal to 0

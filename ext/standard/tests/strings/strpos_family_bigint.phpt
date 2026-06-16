--TEST--
strpos()/stripos()/strrpos()/strripos(): a bigint $offset can't lie within the haystack
--EXTENSIONS--
zend_test
--FILE--
<?php
$haystack = 'hello world';

// A fitting bigint offset behaves like the equivalent int (frameless strpos path).
var_dump(strpos($haystack, 'o', zend_test_make_bigint('5')));

// An out-of-range bigint offset can't lie within the haystack (frameless strpos path).
try {
    strpos($haystack, 'o', 2 ** 100);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// The whole strpos family rejects a bigint offset of either sign.
foreach (['strpos', 'stripos', 'strrpos', 'strripos'] as $fn) {
    try {
        $fn($haystack, 'o', 2 ** 100);
    } catch (ValueError $e) {
        echo $e->getMessage() . "\n";
    }
    try {
        $fn($haystack, 'o', -(2 ** 100));
    } catch (ValueError $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
int(7)
strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
stripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
stripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)

--TEST--
mb_strpos()/mb_strrpos()/mb_stripos()/mb_strripos(): a big-integer $offset falls outside the haystack, not the int range
--EXTENSIONS--
mbstring
zend_test
--FILE--
<?php
$h = 'hello world hello';

// In-range bigint offsets behave like the equivalent int.
var_dump(mb_strpos($h, 'hello', zend_test_make_bigint('6'), 'UTF-8'));
var_dump(mb_strrpos($h, 'hello', zend_test_make_bigint('0'), 'UTF-8'));
var_dump(mb_stripos($h, 'HELLO', zend_test_make_bigint('6'), 'UTF-8'));
var_dump(mb_strripos($h, 'HELLO', zend_test_make_bigint('0'), 'UTF-8'));

// Out-of-range big integers can't be contained in the haystack (both signs).
foreach (['mb_strpos', 'mb_strrpos', 'mb_stripos', 'mb_strripos'] as $fn) {
    try {
        $fn($h, 'hello', 2 ** 100, 'UTF-8');
    } catch (ValueError $e) {
        echo $e->getMessage() . "\n";
    }
    try {
        $fn($h, 'hello', -(2 ** 100), 'UTF-8');
    } catch (ValueError $e) {
        echo $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
int(12)
int(12)
int(12)
int(12)
mb_strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_stripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_stripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_strripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
mb_strripos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)

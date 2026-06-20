--TEST--
Bigint: frameless int parameters accept an out-of-range numeric string
--EXTENSIONS--
zend_test
--FILE--
<?php
// Frameless Z_FLF_PARAM_INT: an out-of-range integer string is accepted as a bigint.
var_dump(zend_test_flf_int('18446744073709551616') === 2 ** 64);
var_dump(zend_test_flf_int_or_null('18446744073709551616') === 2 ** 64);

// The frameless operand is a live variable; weak coercion must not mutate it.
$s = '18446744073709551616';
zend_test_flf_int($s);
var_dump($s);

// Real frameless int consumers handle an out-of-range string offset.
var_dump(substr('hello', '99999999999999999999'));
var_dump(dirname('/a/b/c/d', '99999999999999999999'));
try {
    strpos('hello world', 'o', '99999999999999999999');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// The digit limit applies on the frameless path too.
ini_set('zend.int_string_max_digits', 640);
try {
    zend_test_flf_int(str_repeat('9', 700));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(true)
bool(true)
string(20) "18446744073709551616"
string(0) ""
string(1) "/"
strpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

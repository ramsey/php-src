--TEST--
Bigint: base conversions honor zend.int_string_max_digits for libtommath backend
--EXTENSIONS--
zend_test
--SKIPIF--
<?php if (zend_test_bigint_backend() !== 'libtommath') die('skip libtommath backend only'); ?>
--INI--
zend.int_string_max_digits=640
opcache.enable_cli=0
--FILE--
<?php
$big = 2 ** 3000;

foreach (['dechex', 'decoct', 'decbin'] as $fn) {
    try {
        $fn($big);
    } catch (ValueError $e) {
        echo $fn . ': ' . $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
dechex: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
decoct: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
decbin: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

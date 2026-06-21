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

$cases = [['hexdec', 'f'], ['bindec', '1'], ['octdec', '7']];
foreach ($cases as [$fn, $digit]) {
    try {
        $fn(str_repeat($digit, 700));
    } catch (ValueError $e) {
        echo $fn . ': ' . $e->getMessage() . "\n";
    }
}

try {
    base_convert(str_repeat('f', 700), 16, 10);
} catch (ValueError $e) {
    echo 'base_convert: ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
dechex: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
decoct: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
decbin: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
hexdec: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
bindec: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
octdec: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
base_convert: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

--TEST--
Bigint: number_format() honors zend.int_string_max_digits
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$big = 10 ** 700;
try {
    number_format($big);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

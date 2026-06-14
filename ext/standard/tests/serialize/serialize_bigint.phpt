--TEST--
serialize() emits a bigint as a decimal integer and enforces the digit limit
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$b = 2 ** 100;
echo serialize($b) . "\n";
echo serialize(-$b) . "\n";

try {
    serialize(10 ** 700);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
i:1267650600228229401496703205376;
i:-1267650600228229401496703205376;
Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

--TEST--
NumberFormatter::format() formats an out-of-int64 (bigint) value exactly
--EXTENSIONS--
intl
--FILE--
<?php
$f = new NumberFormatter('en_US', NumberFormatter::DECIMAL);

// A bigint formats with its exact digits, not a rounded double.
echo preg_replace('/[^0-9]/', '', $f->format(2 ** 70)), "\n";
echo preg_replace('/[^0-9]/', '', $f->format(2 ** 70 + 1)), "\n";

// The decimal conversion honors zend.int_string_max_digits.
ini_set('zend.int_string_max_digits', '640');
try {
    $f->format(10 ** 700);
} catch (\ValueError $e) {
    echo $e->getMessage(), "\n";
}
?>
--EXPECT--
1180591620717411303424
1180591620717411303425
Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

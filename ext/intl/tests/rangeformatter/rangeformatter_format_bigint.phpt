--TEST--
IntlNumberRangeFormatter::format() preserves out-of-int64 (bigint) precision
--EXTENSIONS--
intl
--SKIPIF--
<?php if (!class_exists('IntlNumberRangeFormatter')) die('skip IntlNumberRangeFormatter not available'); ?>
--FILE--
<?php

$formatter = IntlNumberRangeFormatter::createFromSkeleton(
    '',
    'en_US',
    IntlNumberRangeFormatter::COLLAPSE_AUTO,
    IntlNumberRangeFormatter::IDENTITY_FALLBACK_SINGLE_VALUE
);

// A bigint endpoint formats with its exact digits, not a rounded double.
$a = preg_replace('/[^0-9]/', '', $formatter->format(2 ** 70, 2 ** 70));
$b = preg_replace('/[^0-9]/', '', $formatter->format(2 ** 70 + 1, 2 ** 70 + 1));
var_dump($a);
var_dump($b);
var_dump($a !== $b);

// The decimal conversion honors zend.int_string_max_digits.
ini_set('zend.int_string_max_digits', '640');
try {
    $formatter->format(10 ** 700, 10 ** 700);
} catch (\ValueError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
string(22) "1180591620717411303424"
string(22) "1180591620717411303425"
bool(true)
Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

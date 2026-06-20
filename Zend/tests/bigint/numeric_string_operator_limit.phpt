--TEST--
Bigint: integer operators honor zend.int_string_max_digits (catchable ValueError)
--FILE--
<?php
ini_set('zend.int_string_max_digits', 640);
$over = str_repeat('9', 700);     // over the 640-digit limit
$s = (string) (PHP_INT_MAX + 1);  // promotable (within the limit)

$cases = [
    'mod'   => fn() => $over % 2,
    'and'   => fn() => $over & 1,
    'or'    => fn() => $over | 0,
    'xor'   => fn() => $over ^ 0,
    'shl'   => fn() => $over << 1,
    'shr'   => fn() => $over >> 1,
    'count' => fn() => 1 << $over,   // over-limit as a shift count throws at conversion
    'split' => fn() => $s % $over,   // op1 promotes, op2 trips the limit (releases op1)
];

foreach ($cases as $label => $op) {
    try {
        $op();
        echo $label . ": no error\n";
    } catch (ValueError $e) {
        echo $label . ': ' . $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
mod: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
and: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
or: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
xor: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
shl: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
shr: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
count: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
split: Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

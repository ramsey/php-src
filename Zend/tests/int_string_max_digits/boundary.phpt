--TEST--
zend.int_string_max_digits: exact boundary, negative magnitude, and unlimited
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$at      = 10 ** 639;     // exactly 640 digits
$over    = 10 ** 640;     // 641 digits
$negAt   = -(10 ** 639);  // 640 magnitude digits (sign not counted)
$negOver = -(10 ** 640);  // 641 magnitude digits

echo 'at: ' . strlen((string) $at) . "\n";        // 640 digits converts
echo 'negAt: ' . strlen((string) $negAt) . "\n";  // 641 chars ('-' + 640 digits) converts (sign excluded)

try {
    (string) $over;
    echo "over: no throw\n";
} catch (ValueError $e) {
    echo "over: threw\n";
}

try {
    (string) $negOver;
    echo "negOver: no throw\n";
} catch (ValueError $e) {
    echo "negOver: threw\n";
}

ini_set('zend.int_string_max_digits', '0');          // unlimited
echo 'unlimited: ' . strlen((string) $over) . "\n";  // 641 digits converts
?>
--EXPECT--
at: 640
negAt: 641
over: threw
negOver: threw
unlimited: 641

--TEST--
zend.int_string_max_digits: int->string conversions throw ValueError over the limit
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$under = 10 ** 639; // 640 digits: at the limit, converts
$over  = 10 ** 640; // 641 digits: over the limit, throws

var_dump(strlen((string) $under));

try {
    $s = (string) $over;
    echo "no throw\n";
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

try {
    echo $over;
} catch (ValueError $e) {
    echo "echo threw\n";
}

try {
    $x = "n=" . $over;
} catch (ValueError $e) {
    echo "concat threw\n";
}

try {
    $x = sprintf("%s", $over);
} catch (ValueError $e) {
    echo "sprintf threw\n";
}

ini_set('zend.int_string_max_digits', '0'); // unlimited disables the check
var_dump(strlen((string) $over));

ini_set('zend.int_string_max_digits', '640');
(string) PHP_INT_MAX; // a normal long is never affected
echo "long ok\n";
?>
--EXPECT--
int(640)
Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
echo threw
concat threw
sprintf threw
int(641)
long ok

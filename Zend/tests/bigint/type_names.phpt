--TEST--
bigint: gettype, get_debug_type, and value-name error text all report a boxed integer as int
--FILE--
<?php
$pos = 340282366920938463463374607431768211456;
var_dump(gettype($pos));
var_dump(get_debug_type($pos));

set_error_handler(static function (int $errno, string $errstr): bool {
    echo $errstr . "\n";
    return true;
});
$pos[0];
?>
--EXPECT--
string(7) "integer"
string(3) "int"
Trying to access array offset on int

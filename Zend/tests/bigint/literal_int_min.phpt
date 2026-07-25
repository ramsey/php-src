--TEST--
bigint: the most negative integer literal parses as a canonical int at each width
--FILE--
<?php
var_dump(-9223372036854775808 < 0, is_int(-9223372036854775808));
var_dump(-2147483648 < 0, is_int(-2147483648));

if (PHP_INT_SIZE === 8) {
    var_dump(-9223372036854775808 === PHP_INT_MIN);
} else {
    var_dump(-2147483648 === PHP_INT_MIN);
}

debug_zval_dump(PHP_INT_MIN);
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
int(-%d)

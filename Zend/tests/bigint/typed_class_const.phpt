--TEST--
Bigint: typed class constant of type int accepts a big integer
--INI--
opcache.enable_cli=0
--FILE--
<?php
class C {
    const int BIG_POSITIVE = 10 ** 30;
    const int BIG_NEGATIVE = -10 ** 30;

    // Standard integers (using 32-bit values, since they work on both 32-bit and 64-bit platforms).
    const int INT_MAX_32BIT = 2147483647;
    const int INT_MIN_32BIT = -2147483648;
}

var_dump(C::BIG_POSITIVE);
var_dump(is_int(C::BIG_POSITIVE));

var_dump(C::BIG_NEGATIVE);
var_dump(is_int(C::BIG_NEGATIVE));

var_dump(C::INT_MAX_32BIT);
var_dump(is_int(C::INT_MAX_32BIT));

var_dump(C::INT_MIN_32BIT);
var_dump(is_int(C::INT_MIN_32BIT));
?>
--EXPECT--
int(1000000000000000000000000000000)
bool(true)
int(-1000000000000000000000000000000)
bool(true)
int(2147483647)
bool(true)
int(-2147483648)
bool(true)

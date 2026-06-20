--TEST--
Bigint: two out-of-range integer strings compare by exact magnitude (strcmp() stays byte-based)
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Different significant length.
var_dump("10000000000000000000" <=> "9999999999999999999");
var_dump("10000000000000000000" > "9999999999999999999");
var_dump("9999999999999999999" < "10000000000000000000");
var_dump("10000000000000000000" == "9999999999999999999");

// Consistent with in-range, which already orders numerically.
var_dump("10000" > "9999");

// Negative out-of-range integers (sign-adjusted magnitude).
var_dump("-10000000000000000000" <=> "-9999999999999999999");
var_dump("-9999999999999999998" <=> "-9999999999999999999");

// Same length, adjacent.
var_dump("9223372036854775808" <=> "9223372036854775809");
var_dump("9223372036854775809" <=> "9223372036854775808");

// The string is still numeric when it contains leading zeroes.
var_dump("09223372036854775808" == "9223372036854775808");
var_dump("09223372036854775808" <=> "9223372036854775808");

// strcmp() is unchanged; it remains byte-based.
var_dump(strcmp("10000000000000000000", "9999999999999999999") < 0);
var_dump(strcmp("10000", "9999") < 0);
?>
--EXPECT--
int(1)
bool(true)
bool(true)
bool(false)
bool(true)
int(-1)
int(1)
int(-1)
int(1)
bool(true)
int(0)
bool(true)
bool(true)

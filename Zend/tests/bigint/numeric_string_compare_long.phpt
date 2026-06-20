--TEST--
Bigint: an int compares against an out-of-range numeric string exactly
--INI--
opcache.enable_cli=0
--FILE--
<?php
// a long can never equal an out-of-range integer string
var_dump(PHP_INT_MAX == (string) (PHP_INT_MAX + 1));
var_dump(PHP_INT_MAX <=> (string) (PHP_INT_MAX + 1));
var_dump(PHP_INT_MIN == (string) (PHP_INT_MIN - 1));
var_dump(PHP_INT_MIN <=> (string) (PHP_INT_MIN - 1));

// far from the boundary
var_dump(5 <=> "9223372036854775808");
var_dump(5 <=> "-9223372036854775809");

// in-range string behavior is unchanged
var_dump(5 <=> "5");
var_dump(5 <=> "6");

// genuine float strings still compare both operands as double
var_dump(5 <=> "1.5");
var_dump(5 <=> "1e300");

// an int vs a numeric string == that int vs the bigint it represents
var_dump((PHP_INT_MAX <=> (string) (PHP_INT_MAX + 1)) === (PHP_INT_MAX <=> (PHP_INT_MAX + 1)));
?>
--EXPECT--
bool(false)
int(-1)
bool(false)
int(1)
int(-1)
int(1)
int(0)
int(-1)
int(1)
int(-1)
bool(true)

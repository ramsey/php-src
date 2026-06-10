--TEST--
intdiv functionality
--SKIPIF--
<?php
if (PHP_INT_SIZE !== 8) {
    die("skip this test is for 64-bit platforms only");
}
?>
--FILE--
<?php
// (int)(PHP_INT_MAX / 3) gives a different result
var_dump(intdiv(PHP_INT_MAX, 3));

var_dump(intdiv(PHP_INT_MIN, -1));
?>
--EXPECT--
int(3074457345618258602)
int(9223372036854775808)

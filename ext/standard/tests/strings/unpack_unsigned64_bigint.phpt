--TEST--
unpack() decodes an unsigned 64-bit value above PHP_INT_MAX as an integer (bigint)
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--FILE--
<?php
// Unsigned big-endian (J).
var_dump(unpack('J', "\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF")[1]);
var_dump(unpack('J', "\x80\x00\x00\x00\x00\x00\x00\x01")[1]);

// A value that still fits a signed long stays a plain int.
var_dump(unpack('J', "\x7F\xFF\xFF\xFF\xFF\xFF\xFF\xFF")[1]);

// Round-trips through pack() preserve the value for every unsigned 64-bit code.
var_dump(unpack('J', pack('J', 9223372036854775809))[1]);
var_dump(unpack('Q', pack('Q', 18446744073709551615))[1]);
var_dump(unpack('P', pack('P', 18446744073709551615))[1]);

// Signed 'q' keeps two's-complement interpretation (always fits a long).
var_dump(unpack('q', "\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF")[1]);
var_dump(unpack('q', pack('q', -1))[1]);

// The decoded out-of-range value is a true integer.
var_dump(is_int(unpack('J', "\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF")[1]));
?>
--EXPECT--
int(18446744073709551615)
int(9223372036854775809)
int(9223372036854775807)
int(9223372036854775809)
int(18446744073709551615)
int(18446744073709551615)
int(-1)
int(-1)
bool(true)

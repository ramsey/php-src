--TEST--
sprintf(): integer specifiers preserve bigint values via the runtime formatter (sign-magnitude; %u aliases %d)
--INI--
opcache.enable_cli=0
--FILE--
<?php

$pos = 2 ** 70;                    // 1180591620717411303424, a positive bigint
$neg = -$pos;
$hexy = 0xABCDEF0123456789ABCDEF;  // a bigint with hex letters

// %d at full precision, sign-magnitude, honoring the +, width, padding, and alignment flags.
var_dump(sprintf('%+d', $pos) === '+1180591620717411303424');
var_dump(sprintf('%+d', $neg) === '-1180591620717411303424');
var_dump(sprintf('%030d', $pos) === str_pad('1180591620717411303424', 30, '0', STR_PAD_LEFT));
var_dump(sprintf('%-30d|', $pos) === str_pad('1180591620717411303424', 30) . '|');

// %x/%X/%o/%b of a positive bigint render the full natural base value, consistent with dec*().
var_dump(sprintf('%x', $pos) === dechex($pos));
var_dump(sprintf('%o', $pos) === decoct($pos));
var_dump(sprintf('%b', $pos) === decbin($pos));
var_dump(sprintf('%x', $pos) === '4' . str_repeat('0', 17));
var_dump(sprintf('%b', $pos) === '1' . str_repeat('0', 70));

// %x lowercase, %X uppercase.
var_dump(sprintf('%x', $hexy) === 'abcdef0123456789abcdef');
var_dump(sprintf('%X', $hexy) === 'ABCDEF0123456789ABCDEF');

// Negative bigints are sign-magnitude (matching dec*), not a fixed-width pattern.
var_dump(sprintf('%x', $neg) === '-' . dechex($pos));
var_dump(sprintf('%o', $neg) === '-' . decoct($pos));
var_dump(sprintf('%b', $neg) === '-' . decbin($pos));

// %u is a deprecated alias of %d; it renders the signed value, both for a bigint and a long.
var_dump(sprintf('%u', $pos) === '1180591620717411303424');
var_dump(sprintf('%u', $neg) === '-1180591620717411303424');
var_dump(sprintf('%u', -1) === '-1');

// Output beyond zend.int_string_max_digits throws a catchable ValueError.
try {
    sprintf('%050x', 16 ** 5000);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
Integer too large to convert to string; it exceeds the limit of 4300 digits, configurable via the zend.int_string_max_digits setting

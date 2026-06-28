--TEST--
FILTER_VALIDATE_INT accepts a sign on octal and hex values (sign-magnitude)
--EXTENSIONS--
filter
--FILE--
<?php
$oct = ['flags' => FILTER_FLAG_ALLOW_OCTAL];
$hex = ['flags' => FILTER_FLAG_ALLOW_HEX];

// Negative octal, mirroring octdec('-017') === -15.
var_dump(filter_var('-017', FILTER_VALIDATE_INT, $oct));
var_dump(filter_var('-0666', FILTER_VALIDATE_INT, $oct));
var_dump(filter_var('-0o17', FILTER_VALIDATE_INT, $oct));
var_dump(filter_var('-0O17', FILTER_VALIDATE_INT, $oct));

// A leading '+' is accepted too.
var_dump(filter_var('+017', FILTER_VALIDATE_INT, $oct));

// Negative hex, mirroring hexdec('-0xff') === -255.
var_dump(filter_var('-0xff', FILTER_VALIDATE_INT, $hex));
var_dump(filter_var('-0XFF', FILTER_VALIDATE_INT, $hex));
var_dump(filter_var('-0xff0000', FILTER_VALIDATE_INT, $hex));
var_dump(filter_var('+0x1f', FILTER_VALIDATE_INT, $hex));

// The leading zero after the sign selects octal; without it the value is decimal.
var_dump(filter_var('-15', FILTER_VALIDATE_INT, $oct));
var_dump(filter_var('-015', FILTER_VALIDATE_INT, $oct));

// A signed decimal is unaffected and stays PHP_INT_MIN-safe.
var_dump(filter_var('-9', FILTER_VALIDATE_INT, $oct));
var_dump(filter_var((string) PHP_INT_MIN, FILTER_VALIDATE_INT, $oct));

// Signed zero.
var_dump(filter_var('-0', FILTER_VALIDATE_INT, $oct));

// Range checks still apply to the signed result.
var_dump(filter_var('-017', FILTER_VALIDATE_INT, ['flags' => FILTER_FLAG_ALLOW_OCTAL, 'options' => ['min_range' => -20, 'max_range' => 0]]));
var_dump(filter_var('-017', FILTER_VALIDATE_INT, ['flags' => FILTER_FLAG_ALLOW_OCTAL, 'options' => ['min_range' => 0]]));

// A sign on an octal/hex value still requires the corresponding flag.
var_dump(filter_var('-017', FILTER_VALIDATE_INT));
var_dump(filter_var('-0xff', FILTER_VALIDATE_INT));
?>
--EXPECT--
int(-15)
int(-438)
int(-15)
int(-15)
int(15)
int(-255)
int(-255)
int(-16711680)
int(31)
int(-15)
int(-13)
int(-9)
int(-9223372036854775808)
int(0)
int(-15)
bool(false)
bool(false)
bool(false)
--TEST--
Bigint: octdec() returns a bigint on overflow and parses a sign
--INI--
opcache.enable_cli=0
--FILE--
<?php
// Overflow results in exact bigint instead of a lossy float (8**22 == 2**66).
var_dump(octdec('1' . str_repeat('0', 22)) === 2 ** 66);
var_dump(octdec('377') === 255);

// Signed input.
var_dump(octdec('-377') === -255);
var_dump(octdec('-0o377') === -255);
var_dump(octdec('+377') === 255);
var_dump(octdec('+0o377') === 255);

// Round-trips with decoct.
var_dump(octdec(decoct(2 ** 90)) === 2 ** 90);
var_dump(octdec(decoct(-(2 ** 90))) === -(2 ** 90));
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

--TEST--
Bigint: the frameless dechex() path accepts a bigint via Z_FLF_PARAM_INT
--INI--
opcache.enable_cli=0
--EXTENSIONS--
zend_test
--FILE--
<?php
// dechex() has a @frameless-function {"arity": 1} annotation; calling it with a
// literal positional argument exercises the ZEND_FRAMELESS_FUNCTION(dechex, 1)
// opcode path, which now uses Z_FLF_PARAM_INT and accepts a bigint.

// Out-of-long-range bigint via FLF is converted.
var_dump(dechex(2 ** 64) === '10000000000000000');
var_dump(dechex(2 ** 32) === '100000000');

// A numeric string coerces to a bigint in the FLF tmp (weak mode), which
// Z_FLF_PARAM_FREE_INT must release.
var_dump(dechex('18446744073709551616') === '10000000000000000');
var_dump(dechex('4294967296') === '100000000');

// In-range non-canonical bigint via FLF uses lossless conversion.
$b = zend_test_make_bigint('255');
var_dump(dechex($b));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
string(2) "ff"

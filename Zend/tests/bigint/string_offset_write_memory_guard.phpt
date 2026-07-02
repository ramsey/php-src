--TEST--
Bigint: writing to a string at an over-large integer offset throws a catchable MemoryError
--INI--
memory_limit=64M
opcache.enable_cli=0
--FILE--
<?php

$e = new MemoryError('boom');
var_dump($e instanceof Error);

// A positive bigint offset would need an ~8 EB string: throw and leave the
// string unchanged instead of aborting with an uncatchable out-of-memory fatal.
$s = 'abc';
try {
    $s[2 ** 70] = 'Z';
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}
var_dump($s);

// The throw unwinds the whole assignment, so the outer target keeps its previous value.
$s = 'abc';
$r = 'untouched';
try {
    $r = ($s[2 ** 70] = 'Z');
} catch (MemoryError $e) {
    echo "caught\n";
}
var_dump($r);
var_dump($s);

// A plain int offset (PHP_INT_MAX) that would exceed memory throws the same MemoryError.
$s = 'abc';
try {
    $s[PHP_INT_MAX] = 'Z';
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}
var_dump($s);

// A negative out-of-range offset still warns and is a no-op (unchanged behavior).
$s = 'abc';
$s[-(2 ** 70)] = 'Z';
var_dump($s);

?>
--EXPECTF--
bool(true)
String offset assignment produces a string too large to fit in the configured memory limit
string(3) "abc"
caught
string(9) "untouched"
string(3) "abc"
String offset assignment produces a string too large to fit in the configured memory limit
string(3) "abc"

Warning: Illegal string offset -1180591620717411303424 in %s on line %d
string(3) "abc"

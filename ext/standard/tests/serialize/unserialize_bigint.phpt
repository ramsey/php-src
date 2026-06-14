--TEST--
unserialize() produces a bigint for an out-of-range integer
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$b = 2 ** 100;

// Round-trips losslessly.
$r = unserialize(serialize($b));
var_dump($r === $b);
var_dump($r === -unserialize(serialize(-$b)));

// A bare decimal unserializes to the same value.
var_dump(unserialize('i:1267650600228229401496703205376;') === $b);
var_dump(unserialize('i:-1267650600228229401496703205376;') === -$b);

// In-range integers stay plain ints (and the type is preserved).
var_dump(unserialize('i:42;') === 42);
var_dump(unserialize('i:-42;') === -42);

// A zero-padded small value still demotes to a plain int.
var_dump(unserialize('i:000000000000000000000000000042;') === 42);

// An over-limit literal throws the same ValueError as the display side.
try {
    unserialize('i:' . str_repeat('9', 641) . ';');
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
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

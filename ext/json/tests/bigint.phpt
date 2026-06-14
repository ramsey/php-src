--TEST--
json_encode()/json_decode() handle bigint integers
--EXTENSIONS--
json
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$b = 2 ** 100;

// Encode emits a bare decimal integer.
echo json_encode($b) . "\n";
echo json_encode(-$b) . "\n";

// Decode of an out-of-range integer literal yields a bigint by default.
var_dump(json_decode('1267650600228229401496703205376') === $b);
var_dump(json_decode('-1267650600228229401496703205376') === -$b);

// Round-trips losslessly.
var_dump(json_decode(json_encode($b)) === $b);

// In-range integers stay plain ints.
var_dump(json_decode('42') === 42);

// JSON_BIGINT_AS_STRING still yields a string.
var_dump(json_decode('1267650600228229401496703205376', false, 512, JSON_BIGINT_AS_STRING));

// json_validate() accepts an out-of-range integer literal.
var_dump(json_validate('1267650600228229401496703205376'));

// Over-limit literal on encode throws ValueError.
try {
    json_encode(10 ** 700);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// Over-limit literal on decode throws ValueError, even without JSON_THROW_ON_ERROR.
try {
    json_decode(str_repeat('9', 641));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
1267650600228229401496703205376
-1267650600228229401496703205376
bool(true)
bool(true)
bool(true)
bool(true)
string(31) "1267650600228229401496703205376"
bool(true)
Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
Integer string too large to convert; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

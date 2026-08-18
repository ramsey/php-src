--TEST--
bigint: an integer string offset outside int range behaves like a boxed offset
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$s = 'abc';

check('read at 19 digits', $s['9223372036854775808']);
check('read at 20 digits', $s['99999999999999999999']);
check('read at 21 digits', $s['111111111111111111111']);
check('read at 31 digits', $s['1267650600228229401496703205376']);
check('the boxed spelling of 31 digits', $s[1267650600228229401496703205376]);

check('read before the start at 19 digits', $s['-9223372036854775809']);
check('read before the start at 21 digits', $s['-111111111111111111111']);
check('read before the start at 31 digits', $s['-1267650600228229401496703205376']);
check('the boxed spelling of -31 digits', $s[-1267650600228229401496703205376]);

check('leading plus', $s['+1267650600228229401496703205376']);
check('leading zeros', $s['001267650600228229401496703205376']);
check('surrounding whitespace', $s['  1267650600228229401496703205376  ']);
check('negative with leading zeros', $s['-001267650600228229401496703205376']);

check('coalesce past the end', $s['111111111111111111111'] ?? 'fallback');
check('isset past the end', isset($s['111111111111111111111']));
check('empty past the end', empty($s['111111111111111111111']));
check('isset before the start', isset($s['-111111111111111111111']));
check('in-range offset', $s['1']);
check('in-range offset past the end', $s['5']);
check('in-range offset with leading zeros', $s['0099']);

check('trailing data in range', $s['5abc']);
check('trailing data outside int range', $s['1267650600228229401496703205376abc']);

try {
    $s['abc'];
} catch (TypeError $e) {
    check('non-numeric offset', $e::class . ': ' . $e->getMessage());
}

try {
    $s['1.5'];
} catch (TypeError $e) {
    check('float shaped offset', $e::class . ': ' . $e->getMessage());
}

try {
    $s['1267650600228229401496703205376.5'];
} catch (TypeError $e) {
    check('float shaped offset outside int range', $e::class . ': ' . $e->getMessage());
}

$write21 = 'abc';
$write21['-111111111111111111111'] = 'x';
check('write before the start at 21 digits', $write21);

$write31 = 'abc';
$write31['-1267650600228229401496703205376'] = 'x';
check('write before the start at 31 digits', $write31);

$writeBoxed = 'abc';
$writeBoxed[-1267650600228229401496703205376] = 'x';
check('the boxed spelling of the same write', $writeBoxed);

$writeZeros = 'abc';
$writeZeros['-001267650600228229401496703205376'] = 'x';
check('write with leading zeros', $writeZeros);

$compound = 'abc';
try {
    $compound['1267650600228229401496703205376'] .= 'x';
} catch (Error $e) {
    check('assign-op is rejected for an offset outside int range', $e::class . ': ' . $e->getMessage());
}
?>
--EXPECTF--
Warning: Uninitialized string offset 9223372036854775808 in %s on line %d
read at 19 digits: string(0) ""

Warning: Uninitialized string offset 99999999999999999999 in %s on line %d
read at 20 digits: string(0) ""

Warning: Uninitialized string offset 111111111111111111111 in %s on line %d
read at 21 digits: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
read at 31 digits: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
the boxed spelling of 31 digits: string(0) ""

Warning: Uninitialized string offset -9223372036854775809 in %s on line %d
read before the start at 19 digits: string(0) ""

Warning: Uninitialized string offset -111111111111111111111 in %s on line %d
read before the start at 21 digits: string(0) ""

Warning: Uninitialized string offset -1267650600228229401496703205376 in %s on line %d
read before the start at 31 digits: string(0) ""

Warning: Uninitialized string offset -1267650600228229401496703205376 in %s on line %d
the boxed spelling of -31 digits: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
leading plus: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
leading zeros: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
surrounding whitespace: string(0) ""

Warning: Uninitialized string offset -1267650600228229401496703205376 in %s on line %d
negative with leading zeros: string(0) ""
coalesce past the end: string(8) "fallback"
isset past the end: bool(false)
empty past the end: bool(true)
isset before the start: bool(false)
in-range offset: string(1) "b"

Warning: Uninitialized string offset 5 in %s on line %d
in-range offset past the end: string(0) ""

Warning: Uninitialized string offset 99 in %s on line %d
in-range offset with leading zeros: string(0) ""

Warning: Illegal string offset "5abc" in %s on line %d

Warning: Uninitialized string offset 5 in %s on line %d
trailing data in range: string(0) ""

Warning: Illegal string offset "1267650600228229401496703205376abc" in %s on line %d

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
trailing data outside int range: string(0) ""
non-numeric offset: string(56) "TypeError: Cannot access offset of type string on string"
float shaped offset: string(56) "TypeError: Cannot access offset of type string on string"
float shaped offset outside int range: string(56) "TypeError: Cannot access offset of type string on string"

Warning: Illegal string offset -111111111111111111111 in %s on line %d
write before the start at 21 digits: string(3) "abc"

Warning: Illegal string offset -1267650600228229401496703205376 in %s on line %d
write before the start at 31 digits: string(3) "abc"

Warning: Illegal string offset -1267650600228229401496703205376 in %s on line %d
the boxed spelling of the same write: string(3) "abc"

Warning: Illegal string offset -1267650600228229401496703205376 in %s on line %d
write with leading zeros: string(3) "abc"
assign-op is rejected for an offset outside int range: string(57) "Error: Cannot use assign-op operators with string offsets"

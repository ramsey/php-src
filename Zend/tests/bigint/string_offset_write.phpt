--TEST--
bigint: writing to a string offset outside int range keeps the existing diagnostics
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$small = 'abc';
$small[-999] = 'x';
check('small negative offset leaves the string alone', $small);

$boxed = 'abc';
$boxed[-(2 ** 100)] = 'x';
check('boxed negative offset leaves the string alone', $boxed);

$padded = 'abc';
$padded[5] = 'x';
check('write past the end pads with spaces', $padded);

$rejected = 'abc';
try {
    $rejected[2 ** 100] = '';
} catch (Error $e) {
    check('empty value is rejected before the string grows', $e::class . ': ' . $e->getMessage());
}
check('string unchanged after the rejected write', $rejected);

$compound = 'abc';
try {
    $compound[2 ** 100] .= 'x';
} catch (Error $e) {
    check('assign-op is rejected for a boxed offset', $e::class . ': ' . $e->getMessage());
}
check('string unchanged after the rejected assign-op', $compound);

$incremented = 'abc';
try {
    $incremented[2 ** 100]++;
} catch (Error $e) {
    check('increment is rejected for a boxed offset', $e::class . ': ' . $e->getMessage());
}
check('string unchanged after the rejected increment', $incremented);
?>
--EXPECTF--
Warning: Illegal string offset -999 in %s on line %d
small negative offset leaves the string alone: string(3) "abc"

Warning: Illegal string offset -1267650600228229401496703205376 in %s on line %d
boxed negative offset leaves the string alone: string(3) "abc"
write past the end pads with spaces: string(6) "abc  x"
empty value is rejected before the string grows: string(55) "Error: Cannot assign an empty string to a string offset"
string unchanged after the rejected write: string(3) "abc"
assign-op is rejected for a boxed offset: string(57) "Error: Cannot use assign-op operators with string offsets"
string unchanged after the rejected assign-op: string(3) "abc"
increment is rejected for a boxed offset: string(48) "Error: Cannot increment/decrement string offsets"
string unchanged after the rejected increment: string(3) "abc"

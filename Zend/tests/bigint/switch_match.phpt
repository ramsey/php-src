--TEST--
bigint: switch, match, in_array, and sort over boxes
--EXTENSIONS--
zend_test
--FILE--
<?php
$bigA = zend_test_bigint_make('100000000000000000000');
$bigA2 = zend_test_bigint_make('100000000000000000000');
$bigB = zend_test_bigint_make('100000000000000000001');

switch ($bigA) {
    case $bigB:
        echo "matched-b\n";
        break;
    case $bigA2:
        echo "matched-a\n";
        break;
    default:
        echo "matched-default\n";
}

$result = match ($bigA) {
    $bigB => 'b',
    $bigA2 => 'a',
    default => 'default',
};
echo $result . "\n";

$haystackBoxes = [$bigB, $bigA2, 5, 10];
var_dump(in_array($bigA, $haystackBoxes, true));
var_dump(in_array($bigA, $haystackBoxes, false));

$haystackStrings = ['100000000000000000000', 5, 10];
var_dump(in_array($bigA, $haystackStrings, true));
var_dump(in_array($bigA, $haystackStrings, false));

$mixed = [
    zend_test_bigint_make('100000000000000000001'),
    5,
    zend_test_bigint_make('-100000000000000000001'),
    0,
    -5,
    zend_test_bigint_make('100000000000000000000'),
];
sort($mixed);
foreach ($mixed as $value) {
    echo (zend_test_int_is_boxed($value) ? zend_test_bigint_to_string($value) : (string) $value) . "\n";
}
?>
--EXPECT--
matched-a
a
bool(true)
bool(true)
bool(false)
bool(true)
-100000000000000000001
-5
0
5
100000000000000000000
100000000000000000001

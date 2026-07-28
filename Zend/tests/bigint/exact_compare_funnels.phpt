--TEST--
bigint: comparison funnels through sort, min, max, in_array, switch, match, and usort
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$scrambled = [2 ** 64 + 1, 2.0 ** 64, '18446744073709551617', 2 ** 64];
sort($scrambled);
foreach ($scrambled as $value) {
    var_dump($value);
}

$mixed = [2 ** 64 + 1, 2.0 ** 64, 2 ** 64, '18446744073709551617'];
check('min($mixed)', min($mixed));
check('max($mixed)', max($mixed));

check('in_array(2 ** 64 + 1, [2.0 ** 64], true)', in_array(2 ** 64 + 1, [2.0 ** 64], true));
check('in_array(2 ** 64 + 1, [2.0 ** 64], false)', in_array(2 ** 64 + 1, [2.0 ** 64], false));

$val = 2 ** 64 + 1;
switch ($val) {
    case 2.0 ** 64:
        echo "switch: float-arm\n";
        break;
    default:
        echo "switch: default\n";
}

$result = match (true) {
    $val == 2.0 ** 64 => 'match: float-arm',
    default => 'match: default',
};
echo $result . "\n";

$values = [2 ** 64 + 1, 5, -(2 ** 64 + 1), 0, 2.0 ** 64, 2 ** 64, '18446744073709551617'];
usort($values, fn($a, $b) => $a <=> $b);
foreach ($values as $value) {
    var_dump($value);
}
?>
--EXPECT--
float(1.8446744073709552E+19)
int(18446744073709551616)
int(18446744073709551617)
string(20) "18446744073709551617"
min($mixed): float(1.8446744073709552E+19)
max($mixed): int(18446744073709551617)
in_array(2 ** 64 + 1, [2.0 ** 64], true): bool(false)
in_array(2 ** 64 + 1, [2.0 ** 64], false): bool(false)
switch: default
match: default
int(-18446744073709551617)
int(0)
int(5)
float(1.8446744073709552E+19)
int(18446744073709551616)
int(18446744073709551617)
string(20) "18446744073709551617"

--TEST--
Bigint: ksort()/krsort() order out-of-range integer keys under each sort flag
--FILE--
<?php

// Values encode their key so the sorted order is readable:
//   a = 2**70, b = 5, c = 2**70+1, d = -(2**70)
$base = [2 ** 70 => 'a', 5 => 'b', 2 ** 70 + 1 => 'c', -(2 ** 70) => 'd'];

echo "SORT_REGULAR:\n";
$x = $base;
ksort($x, SORT_REGULAR);
echo implode('', $x) . "\n";
var_dump(array_all($x, fn ($v, $k) => is_int($k)));

echo "krsort SORT_REGULAR:\n";
$x = $base;
krsort($x, SORT_REGULAR);
echo implode('', $x) . "\n";
var_dump(array_all($x, fn ($v, $k) => is_int($k)));

echo "SORT_STRING:\n";
$x = $base;
ksort($x, SORT_STRING);
echo implode('', $x) . "\n";
var_dump(array_all($x, fn ($v, $k) => is_int($k)));

echo "SORT_NATURAL:\n";
$x = $base;
ksort($x, SORT_NATURAL);
echo implode('', $x) . "\n";
var_dump(array_all($x, fn ($v, $k) => is_int($k)));

echo "SORT_NUMERIC integer keys:\n";
$y = [2 ** 70 + 1 => 'c', 2 ** 70 => 'a'];
ksort($y, SORT_NUMERIC);
echo implode('', $y) . "\n";
var_dump(array_all($y, fn ($v, $k) => is_int($k)));

echo "SORT_NUMERIC float-string key:\n";
$z = ['1.5' => 'x', 5 => 'y', '1.4' => 'w'];
ksort($z, SORT_NUMERIC);
echo implode('', $z) . "\n";
var_dump(array_all($z, fn ($v, $k) => is_int($k)));

?>
--EXPECT--
SORT_REGULAR:
dbac
bool(true)
krsort SORT_REGULAR:
cabd
bool(true)
SORT_STRING:
dacb
bool(true)
SORT_NATURAL:
dbac
bool(true)
SORT_NUMERIC integer keys:
ac
bool(true)
SORT_NUMERIC float-string key:
wxy
bool(false)

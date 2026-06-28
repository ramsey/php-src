--TEST--
Bigint: userland callbacks and key-returning functions see an out-of-range key as an int
--FILE--
<?php

$a = [];
$a[2 ** 100] = 'big';
$a[5] = 'small';

echo "uksort:\n";
$keysAreInt = true;
$u = $a;
uksort($u, function ($x, $y) use (&$keysAreInt) {
    $keysAreInt = $keysAreInt && is_int($x) && is_int($y);
    return $x <=> $y;
});
var_dump($keysAreInt);

echo "array_filter USE_KEY:\n";
$r = array_filter($a, fn ($k) => is_int($k) && $k === 2 ** 100, ARRAY_FILTER_USE_KEY);
var_dump(array_keys($r) === [2 ** 100]);

echo "array_filter USE_BOTH:\n";
$bothInt = true;
array_filter($a, function ($v, $k) use (&$bothInt) {
    $bothInt = $bothInt && is_int($k);
    return true;
}, ARRAY_FILTER_USE_BOTH);
var_dump($bothInt);

echo "array_all callback key:\n";
$allInt = array_all($a, fn ($v, $k) => is_int($k));
var_dump($allInt);

echo "array_find_key return:\n";
$k = array_find_key($a, fn ($v, $key) => $key === 2 ** 100);
var_dump(is_int($k));
var_dump($k === 2 ** 100);

echo "array_walk key:\n";
$walkInt = true;
$w = $a;
array_walk($w, function ($v, $k) use (&$walkInt) {
    $walkInt = $walkInt && is_int($k);
});
var_dump($walkInt);

// See if we can surface any memory leaks in the debug-build memory report.
for ($i = 0; $i < 1000; $i++) {
    $t = $a;
    uksort($t, fn ($x, $y) => $x <=> $y);
    array_filter($a, fn ($k) => true, ARRAY_FILTER_USE_KEY);
    array_filter($a, fn ($v, $k) => true, ARRAY_FILTER_USE_BOTH);
    array_all($a, fn ($v, $k) => true);
    array_find_key($a, fn ($v, $k) => false);
    $t2 = $a;
    array_walk($t2, function ($v, $k) {});
}

?>
--EXPECT--
uksort:
bool(true)
array_filter USE_KEY:
bool(true)
array_filter USE_BOTH:
bool(true)
array_all callback key:
bool(true)
array_find_key return:
bool(true)
bool(true)
array_walk key:
bool(true)

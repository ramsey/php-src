--TEST--
bigint: array functions produce and consume big keys as integers
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$a = [
    2 ** 100 => 'x',
    -(2 ** 100) => 'y',
    5 => 'z',
];

check('array_keys', array_keys($a));
check('array_keys with a loose search value', array_keys($a, 'x'));
check('array_keys with a strict search value', array_keys($a, 'x', true));

check('array_flip turns a big value into a big key', array_flip(['a' => 2 ** 100]));
check('array_flip round trip', array_flip(array_flip(['a' => 2 ** 100])));

check('array_search', array_search('x', $a));
check('array_search strict', array_search('x', $a, true));
check('array_search result is identical to the key', array_search('x', $a, true) === 2 ** 100);
check('in_array', in_array('x', $a));

$rows = [
    ['id' => 2 ** 100, 'name' => 'first'],
    ['id' => -(2 ** 100), 'name' => 'second'],
];

check('array_column with a big index key', array_column($rows, 'name', 'id'));
check('array_column with a null column', array_keys(array_column($rows, null, 'id')));

check('array_fill_keys', array_fill_keys([2 ** 100], 'v'));
check('array_combine', array_combine([2 ** 100], ['v']));
check('array_count_values', array_count_values([2 ** 100, 2 ** 100, 5]));

$u = [
    2 ** 100 => 'a',
    5 => 'b',
    -(2 ** 100) => 'c',
];
$sawOnlyInts = true;

uksort($u, function (mixed $x, mixed $y) use (&$sawOnlyInts): int {
    $sawOnlyInts = $sawOnlyInts && is_int($x) && is_int($y);

    return $x <=> $y;
});

check('uksort saw only int keys', $sawOnlyInts);
check('uksort result keys', array_keys($u));

$v = [
    2 ** 100 => 'b',
    5 => 'a',
];
uasort($v, fn (string $x, string $y): int => $x <=> $y);
check('uasort keeps the big key', array_keys($v));

$w = [
    2 ** 100 => 'b',
    5 => 'a',
];
usort($w, fn (string $x, string $y): int => $x <=> $y);
check('usort renumbers', array_keys($w));

check('array_filter by key', array_filter($a, fn (mixed $k): bool => is_int($k), ARRAY_FILTER_USE_KEY));
check('array_filter by key and value', array_filter($a, fn (string $v, mixed $k): bool => is_int($k), ARRAY_FILTER_USE_BOTH));
check('array_find_key', array_find_key($a, fn (string $v, mixed $k): bool => $v === 'x'));

check('array_rand over a single element', array_rand([2 ** 100 => 'x']));
check('array_rand with every element requested', array_rand($a, 3));
?>
--EXPECT--
array_keys: array(3) {
  [0]=>
  int(1267650600228229401496703205376)
  [1]=>
  int(-1267650600228229401496703205376)
  [2]=>
  int(5)
}
array_keys with a loose search value: array(1) {
  [0]=>
  int(1267650600228229401496703205376)
}
array_keys with a strict search value: array(1) {
  [0]=>
  int(1267650600228229401496703205376)
}
array_flip turns a big value into a big key: array(1) {
  [1267650600228229401496703205376]=>
  string(1) "a"
}
array_flip round trip: array(1) {
  ["a"]=>
  int(1267650600228229401496703205376)
}
array_search: int(1267650600228229401496703205376)
array_search strict: int(1267650600228229401496703205376)
array_search result is identical to the key: bool(true)
in_array: bool(true)
array_column with a big index key: array(2) {
  [1267650600228229401496703205376]=>
  string(5) "first"
  [-1267650600228229401496703205376]=>
  string(6) "second"
}
array_column with a null column: array(2) {
  [0]=>
  int(1267650600228229401496703205376)
  [1]=>
  int(-1267650600228229401496703205376)
}
array_fill_keys: array(1) {
  [1267650600228229401496703205376]=>
  string(1) "v"
}
array_combine: array(1) {
  [1267650600228229401496703205376]=>
  string(1) "v"
}
array_count_values: array(2) {
  [1267650600228229401496703205376]=>
  int(2)
  [5]=>
  int(1)
}
uksort saw only int keys: bool(true)
uksort result keys: array(3) {
  [0]=>
  int(-1267650600228229401496703205376)
  [1]=>
  int(5)
  [2]=>
  int(1267650600228229401496703205376)
}
uasort keeps the big key: array(2) {
  [0]=>
  int(5)
  [1]=>
  int(1267650600228229401496703205376)
}
usort renumbers: array(2) {
  [0]=>
  int(0)
  [1]=>
  int(1)
}
array_filter by key: array(3) {
  [1267650600228229401496703205376]=>
  string(1) "x"
  [-1267650600228229401496703205376]=>
  string(1) "y"
  [5]=>
  string(1) "z"
}
array_filter by key and value: array(3) {
  [1267650600228229401496703205376]=>
  string(1) "x"
  [-1267650600228229401496703205376]=>
  string(1) "y"
  [5]=>
  string(1) "z"
}
array_find_key: int(1267650600228229401496703205376)
array_rand over a single element: int(1267650600228229401496703205376)
array_rand with every element requested: array(3) {
  [0]=>
  int(1267650600228229401496703205376)
  [1]=>
  int(-1267650600228229401496703205376)
  [2]=>
  int(5)
}

--TEST--
bigint: key sorts order big keys exactly
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$base = [
    2 ** 64 => 'a',
    -(2 ** 70) => 'b',
    5 => 'c',
    '10000000000000000001' => 'd',
    '42' => 'e',
];

$a = $base;
ksort($a);
check('array_keys after ksort', array_keys($a));

$a = $base;
krsort($a);
check('array_keys after krsort', array_keys($a));

$a = $base;
ksort($a, SORT_STRING);
check('array_keys after ksort SORT_STRING', array_keys($a));

$a = $base;
krsort($a, SORT_STRING);
check('array_keys after krsort SORT_STRING', array_keys($a));

$near = [
    '10000000000000000002' => 'high',
    '10000000000000000001' => 'low',
];

$a = $near;
ksort($a);
check('array_values after ksort on keys that equate as doubles', array_values($a));

$a = $near;
ksort($a, SORT_NUMERIC);
check('array_values after ksort SORT_NUMERIC on keys that equate as doubles', array_values($a));

$a = [
    '10000000000000000001' => 'low',
    '1.5' => 'fraction',
    '20000000000000000000' => 'high',
];
ksort($a, SORT_NUMERIC);
check('array_values after ksort SORT_NUMERIC with a fractional key', array_values($a));

$a = $base;
uksort($a, fn (mixed $x, mixed $y): int => $x <=> $y);
check('array_keys after uksort', array_keys($a));
?>
--EXPECT--
array_keys after ksort: array(5) {
  [0]=>
  int(-1180591620717411303424)
  [1]=>
  int(5)
  [2]=>
  int(42)
  [3]=>
  int(10000000000000000001)
  [4]=>
  int(18446744073709551616)
}
array_keys after krsort: array(5) {
  [0]=>
  int(18446744073709551616)
  [1]=>
  int(10000000000000000001)
  [2]=>
  int(42)
  [3]=>
  int(5)
  [4]=>
  int(-1180591620717411303424)
}
array_keys after ksort SORT_STRING: array(5) {
  [0]=>
  int(-1180591620717411303424)
  [1]=>
  int(10000000000000000001)
  [2]=>
  int(18446744073709551616)
  [3]=>
  int(42)
  [4]=>
  int(5)
}
array_keys after krsort SORT_STRING: array(5) {
  [0]=>
  int(5)
  [1]=>
  int(42)
  [2]=>
  int(18446744073709551616)
  [3]=>
  int(10000000000000000001)
  [4]=>
  int(-1180591620717411303424)
}
array_values after ksort on keys that equate as doubles: array(2) {
  [0]=>
  string(3) "low"
  [1]=>
  string(4) "high"
}
array_values after ksort SORT_NUMERIC on keys that equate as doubles: array(2) {
  [0]=>
  string(3) "low"
  [1]=>
  string(4) "high"
}
array_values after ksort SORT_NUMERIC with a fractional key: array(3) {
  [0]=>
  string(8) "fraction"
  [1]=>
  string(3) "low"
  [2]=>
  string(4) "high"
}
array_keys after uksort: array(5) {
  [0]=>
  int(-1180591620717411303424)
  [1]=>
  int(5)
  [2]=>
  int(42)
  [3]=>
  int(10000000000000000001)
  [4]=>
  int(18446744073709551616)
}

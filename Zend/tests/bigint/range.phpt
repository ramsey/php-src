--TEST--
Bigint: range() produces an exact integer sequence across the PHP_INT_MAX boundary
--INI--
opcache.enable_cli=0
--FILE--
<?php

$r = range(PHP_INT_MAX, PHP_INT_MAX + 3);
var_dump(count($r));
var_dump($r[0] === PHP_INT_MAX);
var_dump(array_map('is_int', $r));
var_dump($r[1] - $r[0], $r[2] - $r[1], $r[3] - $r[2]);

// Both bounds bigint, descending.
echo implode(',', range(2 ** 70 + 2, 2 ** 70)) . "\n";

// Step greater than 1.
echo implode(',', range(2 ** 70, 2 ** 70 + 6, 2)) . "\n";

// Equal bounds yield a single element.
echo implode(',', range(2 ** 70, 2 ** 70)) . "\n";

// A span too large to materialize throws, rather than exhausting memory.
try {
    range(0, 2 ** 70);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

// A step larger than the span is rejected (a bigint step exceeds any range).
try {
    range(2 ** 70, 2 ** 70 + 1, 2 ** 71);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
int(4)
bool(true)
array(4) {
  [0]=>
  bool(true)
  [1]=>
  bool(true)
  [2]=>
  bool(true)
  [3]=>
  bool(true)
}
int(1)
int(1)
int(1)
1180591620717411303426,1180591620717411303425,1180591620717411303424
1180591620717411303424,1180591620717411303426,1180591620717411303428,1180591620717411303430
1180591620717411303424
The supplied range exceeds the maximum array size
range(): Argument #3 ($step) must be less than the range spanned by argument #1 ($start) and argument #2 ($end)

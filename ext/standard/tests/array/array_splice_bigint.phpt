--TEST--
array_splice(): big-integer $offset/$length clamp like out-of-range longs
--EXTENSIONS--
zend_test
--FILE--
<?php
// In-range bigint offset.
$a = [1, 2, 3];
$r = array_splice($a, zend_test_make_bigint('1'));
echo '[' . implode(',', $r) . '] [' . implode(',', $a) . "]\n";

// Positive bigint offset clamps to the end: nothing removed.
$a = [1, 2, 3];
$r = array_splice($a, 2 ** 100);
echo '[' . implode(',', $r) . '] [' . implode(',', $a) . "]\n";

// Negative bigint offset clamps to the start: all removed.
$a = [1, 2, 3];
$r = array_splice($a, -(2 ** 100));
echo '[' . implode(',', $r) . '] [' . implode(',', $a) . "]\n";

// Positive bigint length removes everything from the offset.
$a = [1, 2, 3];
$r = array_splice($a, 1, 2 ** 100);
echo '[' . implode(',', $r) . '] [' . implode(',', $a) . "]\n";

// Negative bigint length removes nothing.
$a = [1, 2, 3];
$r = array_splice($a, 1, -(2 ** 100));
echo '[' . implode(',', $r) . '] [' . implode(',', $a) . "]\n";
?>
--EXPECT--
[2,3] [1]
[] [1,2,3]
[1,2,3] []
[2,3] [1]
[] [1,2,3]

--TEST--
mbstring: a bigint nested in an array is preserved, not dropped
--EXTENSIONS--
mbstring
--FILE--
<?php
$a = ['s' => 'bar', 'n' => 2 ** 100];

// mb_convert_encoding keeps a nested bigint as-is (it used to warn and drop it).
$r = mb_convert_encoding($a, 'UTF-8', 'UTF-8');
var_dump(array_keys($r) === ['s', 'n']);
var_dump($r['n'] === 2 ** 100);

// mb_check_encoding treats a nested bigint as a valid (non-string) scalar.
var_dump(mb_check_encoding($a, 'UTF-8'));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)

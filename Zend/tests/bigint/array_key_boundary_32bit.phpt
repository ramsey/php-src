--TEST--
bigint: array key readback switches to integers exactly at the long boundary on 32-bit
--DESCRIPTION--
The 32-bit sibling of array_key_boundary.phpt, which carries the full
rationale. Here the long range ends at 2147483647, so '2147483648' already
reads back as an integer key while '2147483647' and '-2147483648' stay
string keys.
--SKIPIF--
<?php
if (PHP_INT_SIZE !== 4) die('skip 32-bit only');
?>
--FILE--
<?php
function boundaryNames(): array {
    ${'2147483647'} = 'a';
    ${'2147483648'} = 'b';
    ${'-2147483648'} = 'c';
    ${'-2147483649'} = 'd';
    ${'9223372036854775808'} = 'e';

    return get_defined_vars();
}

var_dump(boundaryNames());
?>
--EXPECT--
array(5) {
  ["2147483647"]=>
  string(1) "a"
  [2147483648]=>
  string(1) "b"
  ["-2147483648"]=>
  string(1) "c"
  [-2147483649]=>
  string(1) "d"
  [9223372036854775808]=>
  string(1) "e"
}

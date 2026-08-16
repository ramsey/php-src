--TEST--
bigint: array key readback switches to integers exactly at the long boundary
--DESCRIPTION--
Writing $array['1234'] gives you the integer key 1234, since normal array writes
convert a numeric string key to an integer whenever the value fits. Variable
names skip that conversion. A variable variable may be named with any string,
and the name is kept verbatim, so get_defined_vars() hands back '1234' as a
string key.

Out-of-range names lose that distinction. An integer too large for an integer
key is stored as its decimal digits in a string key, which is exactly how the
variable name was stored in the first place. Reading the key back, there is
nothing left to say that these digits were a name rather than an integer, so
they are reported as an integer like any other out-of-range digit string, and
the variable named '9223372036854775808' comes back as an integer key.
--SKIPIF--
<?php
if (PHP_INT_SIZE !== 8) die('skip 64-bit only');
?>
--FILE--
<?php
function boundaryNames(): array {
    ${'9223372036854775807'} = 'a';
    ${'9223372036854775808'} = 'b';
    ${'-9223372036854775808'} = 'c';
    ${'-9223372036854775809'} = 'd';
    ${'2147483648'} = 'e';

    return get_defined_vars();
}

var_dump(boundaryNames());
?>
--EXPECT--
array(5) {
  ["9223372036854775807"]=>
  string(1) "a"
  [9223372036854775808]=>
  string(1) "b"
  ["-9223372036854775808"]=>
  string(1) "c"
  [-9223372036854775809]=>
  string(1) "d"
  ["2147483648"]=>
  string(1) "e"
}

--TEST--
testing integer underflow (64bit)
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only"); ?>
--FILE--
<?php

// With the move to arbitrary precision numbers, integers no longer clamp on underflow.

$doubles = array(
        -9223372036854775808,
        -9223372036854775809,
        -9223372036854775818,
        -9223372036854775908,
        -9223372036854776808,
        );

foreach ($doubles as $d) {
        $l = (int)$d;
        var_dump($l);
}

echo "Done\n";
?>
--EXPECT--
int(-9223372036854775808)
int(-9223372036854775809)
int(-9223372036854775818)
int(-9223372036854775908)
int(-9223372036854776808)
Done

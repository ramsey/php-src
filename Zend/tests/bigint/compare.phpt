--TEST--
Bigint: comparison operators (==, !=, ===, !==, <, >, <=, >=, <=>)
--SKIPIF--
<?php if (PHP_INT_SIZE < 8) die("skip this test is for 64-bit platform only"); ?>
--FILE--
<?php
$a = 9223372036854775808;  // literal bigint
$b = PHP_INT_MAX + 1;      // arithmetic bigint, equal in value to $a
$c = 9223372036854775809;  // one larger
$n = PHP_INT_MIN + (-1);   // negative bigint

// equality
var_dump($a == $b);
var_dump($a == $c);
var_dump($a != $c);
var_dump($a != $b);

// identity: two real bigints of equal value are identical
var_dump($a === $b);
var_dump($a === $c);
var_dump($a !== $c);

// ordering between bigints
var_dump($a < $c);
var_dump($c > $a);
var_dump($a <= $b);
var_dump($a >= $b);
var_dump($c < $a);

// spaceship between bigints
var_dump($a <=> $c);
var_dump($c <=> $a);
var_dump($a <=> $b);

// bigint vs long (both directions)
var_dump($a > PHP_INT_MAX);
var_dump($a > 5);
var_dump(PHP_INT_MAX < $a);
var_dump($a <=> 0);
var_dump(0 <=> $a);

// negative bigint
var_dump($n < 0);
var_dump($n < PHP_INT_MIN);
var_dump($n <=> $a);
?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(false)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)
int(-1)
int(1)
int(0)
bool(true)
bool(true)
bool(true)
int(1)
int(-1)
bool(true)
bool(true)
int(-1)

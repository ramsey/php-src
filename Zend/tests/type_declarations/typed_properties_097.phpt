--TEST--
Incrementing/decrementing past max/min does not overflow to float (additional cases)
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die('skip 64 bit test'); ?>
--FILE--
<?php

class Test {
    public int $foo;
}

$test = new Test;

$test->foo = PHP_INT_MIN;
--$test->foo;
var_dump($test->foo);
$test->foo--;
var_dump($test->foo);

$test->foo = PHP_INT_MAX;
++$test->foo;
var_dump($test->foo);
$test->foo++;
var_dump($test->foo);

// Do the same things again, but with the property being a reference.
$ref =& $test->foo;

$test->foo = PHP_INT_MIN;
--$test->foo;
var_dump($test->foo);
$test->foo--;
var_dump($test->foo);

$test->foo = PHP_INT_MAX;
++$test->foo;
var_dump($test->foo);
$test->foo++;
var_dump($test->foo);

?>
--EXPECT--
int(-9223372036854775809)
int(-9223372036854775810)
int(9223372036854775808)
int(9223372036854775809)
int(-9223372036854775809)
int(-9223372036854775810)
int(9223372036854775808)
int(9223372036854775809)

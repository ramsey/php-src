--TEST--
Increment/decrement a typed property with int|float type
--FILE--
<?php

class Test {
    public int|float $prop;
    public int|bool $prop2;
}

/* Incrementing a int|float property past int min/max is legal */

$test = new Test;
$test->prop = PHP_INT_MAX;
$x = $test->prop++;
var_dump(is_int($test->prop));

$test->prop = PHP_INT_MAX;
$x = ++$test->prop;
var_dump(is_int($test->prop));

$test->prop = PHP_INT_MIN;
$x = $test->prop--;
var_dump(is_int($test->prop));

$test->prop = PHP_INT_MIN;
$x = --$test->prop;
var_dump(is_int($test->prop));

$test = new Test;
$test->prop = PHP_INT_MAX;
$r =& $test->prop;
$x = $test->prop++;
var_dump(is_int($test->prop));

$test->prop = PHP_INT_MAX;
$x = ++$test->prop;
$r =& $test->prop;
var_dump(is_int($test->prop));

$test->prop = PHP_INT_MIN;
$x = $test->prop--;
$r =& $test->prop;
var_dump(is_int($test->prop));

$test->prop = PHP_INT_MIN;
$x = --$test->prop;
$r =& $test->prop;
var_dump(is_int($test->prop));

/* Incrementing a non-int|float property past int min/max is also legal. */

$test->prop2 = PHP_INT_MAX;
$x = $test->prop2++;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MAX;
$x = ++$test->prop2;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MIN;
$x = $test->prop2--;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MIN;
$x = --$test->prop2;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MAX;
$r =& $test->prop2;
$x = $test->prop2++;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MAX;
$r =& $test->prop2;
$x = ++$test->prop2;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MIN;
$r =& $test->prop2;
$x = $test->prop2--;
var_dump(is_int($test->prop2));

$test->prop2 = PHP_INT_MIN;
$r =& $test->prop2;
$x = --$test->prop2;
var_dump(is_int($test->prop2));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

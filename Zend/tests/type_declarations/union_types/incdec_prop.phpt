--TEST--
Increment/decrement a typed property with int|float type
--FILE--
<?php

class Test {
    public int|float $prop;
    public int|bool $prop2;
}

/* Incrementing an int|float property past int min/max is legal and stays an
 * integer: overflow promotes to a big integer rather than silently becoming a
 * float, since the slot accepts int. */

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

/* Incrementing a non-float property past int min/max promotes to a bigint,
 * which is accepted by any int-accepting type including int|bool. */

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

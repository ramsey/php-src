--TEST--
round(): a big-integer $precision clamps to the int range like a huge long
--EXTENSIONS--
zend_test
--FILE--
<?php
var_dump(round(3.14159, 2));
var_dump(round(3.14159, zend_test_make_bigint('2')));

// A huge positive precision rounds to no effective places: the value is unchanged.
var_dump(round(3.14159, 2 ** 100));

// A huge negative precision rounds away every digit.
var_dump(round(12345.678, -(2 ** 100)));
?>
--EXPECT--
float(3.14)
float(3.14)
float(3.14159)
float(0)

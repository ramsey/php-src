--TEST--
Check max() optimisation for int and float types
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only"); ?>
--FILE--
<?php

echo "Start as int optimisation:\n";
var_dump(max(10, 5, 3, 2));
var_dump(max(2, 3, 5, 10));
var_dump(max(10, 5, 3.5, 2));
var_dump(max(2, 3.5, 5, 10));
var_dump(max(10, 5, "3", 2));
var_dump(max(2, "3", 5, 10));
var_dump(max(2, 3, "15", 10));
echo "Check that int not representable as float works:\n";
var_dump(max(PHP_INT_MIN+1, PHP_INT_MIN, PHP_INT_MIN*2));
var_dump(max(PHP_INT_MAX-1, PHP_INT_MAX, PHP_INT_MAX*2));
// Operand exceeds float range (exact bigint)
var_dump(max(PHP_INT_MAX-1, PHP_INT_MAX, PHP_INT_MAX**20));

echo "Start as float optimisation:\n";
var_dump(max(10.5, 5.5, 3.5, 2.5));
var_dump(max(2.5, 3.5, 5.5, 10.5));
var_dump(max(10.5, 5.5, 3, 2.5));
var_dump(max(2.5, 3, 5.5, 10.5));
var_dump(max(10.5, 5.5, "3.5", 2.5));
var_dump(max(2.5, "3.5", 5.5, 10.5));
var_dump(max(2.5, 3.5, "15.5", 10.5));
echo "Check that int not representable as float works:\n";
var_dump(max(PHP_INT_MIN*2, PHP_INT_MIN, PHP_INT_MIN+1));
var_dump(max(PHP_INT_MAX*2, PHP_INT_MAX, PHP_INT_MAX-1));
// Operand exceeds float range (exact bigint)
var_dump(max(PHP_INT_MAX**20, PHP_INT_MAX, PHP_INT_MAX-1));

?>
--EXPECT--
Start as int optimisation:
int(10)
int(10)
int(10)
int(10)
int(10)
int(10)
string(2) "15"
Check that int not representable as float works:
int(-9223372036854775807)
int(18446744073709551614)
int(19851555241898344153130788677769001983160921861226045243324013058499644278985263829655458477500554934148319186908254029753701126211729124925712332077145392772214865805846210362859747023907163434176886296071735509937063967304415184441745878591613191784199114531387951675074220397532740510211790749968004592396387387319085094672935864701114816359825685092500704674453832649932800001)
Start as float optimisation:
float(10.5)
float(10.5)
float(10.5)
float(10.5)
float(10.5)
float(10.5)
string(4) "15.5"
Check that int not representable as float works:
int(-9223372036854775807)
int(18446744073709551614)
int(19851555241898344153130788677769001983160921861226045243324013058499644278985263829655458477500554934148319186908254029753701126211729124925712332077145392772214865805846210362859747023907163434176886296071735509937063967304415184441745878591613191784199114531387951675074220397532740510211790749968004592396387387319085094672935864701114816359825685092500704674453832649932800001)

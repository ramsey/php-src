--TEST--
zend_dval_to_lval preserves low bits  (32 bit long)
--SKIPIF--
<?php
if (PHP_INT_SIZE != 4)
     die("skip for machines with 32-bit longs");
?>
--FILE--
<?php
// test doubles around -4e21
$values = [
    -4000000000000001048576.,
    -4000000000000000524288.,
    -4000000000000000000000.,
    -3999999999999999475712.,
    -3999999999999998951424.,
];
// see if we're rounding negative numbers right
$values[] = -2147483649.8;

$str = 'abc';
$format = '%d';

foreach ($values as $v) {
    var_dump($str[$v]);
    var_dump(vsprintf($format, [$v]));
}

?>
--EXPECTF--
Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset -2056257536 in %s on line %d
string(0) ""

Warning: The float -4.000000000000001E+21 is not representable as an int, cast occurred in %s on line %d
string(11) "-2056257536"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset -2055733248 in %s on line %d
string(0) ""

Warning: The float -4.0000000000000005E+21 is not representable as an int, cast occurred in %s on line %d
string(11) "-2055733248"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset -2055208960 in %s on line %d
string(0) ""

Warning: The float -4.0E+21 is not representable as an int, cast occurred in %s on line %d
string(11) "-2055208960"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset -2054684672 in %s on line %d
string(0) ""

Warning: The float -3.9999999999999995E+21 is not representable as an int, cast occurred in %s on line %d
string(11) "-2054684672"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset -2054160384 in %s on line %d
string(0) ""

Warning: The float -3.999999999999999E+21 is not representable as an int, cast occurred in %s on line %d
string(11) "-2054160384"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset 2147483647 in %s on line %d
string(0) ""

Warning: The float -2147483649.8 is not representable as an int, cast occurred in %s on line %d
string(10) "2147483647"

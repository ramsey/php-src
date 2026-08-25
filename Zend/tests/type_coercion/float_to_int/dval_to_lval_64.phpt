--TEST--
zend_dval_to_lval preserves low bits  (64 bit long)
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8)
     die("skip for machines with 64-bit longs");
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

$str = 'abc';
$format = '%d';

foreach ($values as $v) {
    var_dump($str[$v]);
    var_dump(vsprintf($format, [$v]));
}

?>
--EXPECTF--
Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset 2943463994971652096 in %s on line %d
string(0) ""

Warning: The float -4.000000000000001E+21 is not representable as an int, cast occurred in %s on line %d
string(19) "2943463994971652096"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset 2943463994972176384 in %s on line %d
string(0) ""

Warning: The float -4.0000000000000005E+21 is not representable as an int, cast occurred in %s on line %d
string(19) "2943463994972176384"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset 2943463994972700672 in %s on line %d
string(0) ""

Warning: The float -4.0E+21 is not representable as an int, cast occurred in %s on line %d
string(19) "2943463994972700672"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset 2943463994973224960 in %s on line %d
string(0) ""

Warning: The float -3.9999999999999995E+21 is not representable as an int, cast occurred in %s on line %d
string(19) "2943463994973224960"

Warning: String offset cast occurred in %s on line %d

Warning: Uninitialized string offset 2943463994973749248 in %s on line %d
string(0) ""

Warning: The float -3.999999999999999E+21 is not representable as an int, cast occurred in %s on line %d
string(19) "2943463994973749248"

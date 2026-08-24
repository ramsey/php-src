--TEST--
Floats too large for int used as array keys convert exactly
--FILE--
<?php

$float = 10e120;
$string_float = (string) $float;

var_dump((int) $float);
var_dump((int) $string_float === (int) $float);

$arrayConstant = [10e120 => 'Large float', (string) 10e120 => 'String large float'];
$arrayDynamic = [$float => 'Large float', $string_float => 'String large float'];

var_dump($arrayConstant);
var_dump($arrayDynamic);

$array = ['0', '1', '2'];
var_dump($array[10e120]);
var_dump($array[(string) 10e120]);
var_dump($array[$float]);
var_dump($array[$string_float]);

?>
--EXPECTF--
int(10000000000000000373409337471459889719393275754491820381027730410378005080671497101378613371421126415052399029342192009216)
bool(true)
array(2) {
  [10000000000000000373409337471459889719393275754491820381027730410378005080671497101378613371421126415052399029342192009216]=>
  string(11) "Large float"
  ["1.0E+121"]=>
  string(18) "String large float"
}
array(2) {
  [10000000000000000373409337471459889719393275754491820381027730410378005080671497101378613371421126415052399029342192009216]=>
  string(11) "Large float"
  ["1.0E+121"]=>
  string(18) "String large float"
}

Warning: Undefined array key 10000000000000000373409337471459889719393275754491820381027730410378005080671497101378613371421126415052399029342192009216 in %s on line %d
NULL

Warning: Undefined array key "1.0E+121" in %s on line %d
NULL

Warning: Undefined array key 10000000000000000373409337471459889719393275754491820381027730410378005080671497101378613371421126415052399029342192009216 in %s on line %d
NULL

Warning: Undefined array key "1.0E+121" in %s on line %d
NULL

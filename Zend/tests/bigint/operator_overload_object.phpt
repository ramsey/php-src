--TEST--
Bigint: operator-overloaded object reaches its handler when the other operand is a bigint
--EXTENSIONS--
zend_test
--FILE--
<?php

$o = new DoOperationNoCast(17);
$big = 2 ** 100;

var_dump(($o + $big) instanceof DoOperationNoCast);
var_dump(($big + $o) instanceof DoOperationNoCast);
var_dump(($o - $big) instanceof DoOperationNoCast);
var_dump(($big - $o) instanceof DoOperationNoCast);
var_dump(($o * $big) instanceof DoOperationNoCast);
var_dump(($big * $o) instanceof DoOperationNoCast);
var_dump(($o / $big) instanceof DoOperationNoCast);
var_dump(($big / $o) instanceof DoOperationNoCast);
var_dump(($o % $big) instanceof DoOperationNoCast);
var_dump(($big % $o) instanceof DoOperationNoCast);
var_dump(($o & $big) instanceof DoOperationNoCast);
var_dump(($o | $big) instanceof DoOperationNoCast);
var_dump(($o ^ $big) instanceof DoOperationNoCast);
var_dump(($o << $big) instanceof DoOperationNoCast);
var_dump(($o >> $big) instanceof DoOperationNoCast);

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

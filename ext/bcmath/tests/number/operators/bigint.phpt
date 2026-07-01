--TEST--
BcMath\Number operators and comparison accept a bigint operand
--EXTENSIONS--
bcmath
--FILE--
<?php

$big = 2 ** 100;
$s = '1267650600228229401496703205376';
$a = new BcMath\Number('5');

var_dump((string) ($a + $big) === (string) ($a + $s));
var_dump((string) ($big + $a) === (string) ($s + $a));
var_dump((string) ($a - $big) === (string) ($a - $s));
var_dump((string) ($a * $big) === (string) ($a * $s));
var_dump((string) ($a / $big) === (string) ($a / $s));
var_dump((string) ($a % $big) === (string) ($a % $s));

var_dump(($a <=> $big) === ($a <=> $s));
var_dump(($a < $big) === ($a < $s));
var_dump(($a == $big) === ($a == $s));

var_dump((string) ($a + $big));

$eBig = null;
$eStr = null;

try {
    $a ** $big;
} catch (ValueError $e) {
    $eBig = $e->getMessage();
}

try {
    $a ** $s;
} catch (ValueError $e) {
    $eStr = $e->getMessage();
}

var_dump($eBig !== null && $eBig === $eStr);

try {
    $a + (10 ** 5000);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

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
string(31) "1267650600228229401496703205381"
bool(true)
Integer too large to convert to string; it exceeds the limit of 4300 digits, configurable via the zend.int_string_max_digits setting

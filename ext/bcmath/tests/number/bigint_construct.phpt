--TEST--
BcMath\Number construct accepts a bigint
--EXTENSIONS--
bcmath
--FILE--
<?php

$num = new BcMath\Number(2 ** 100);
var_dump((string) $num);
var_dump($num->scale);

$neg = new BcMath\Number(-(2 ** 100));
var_dump((string) $neg);

var_dump((string) new BcMath\Number(2 ** 100) === (string) new BcMath\Number('1267650600228229401496703205376'));

try {
    new BcMath\Number(10 ** 5000);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

ini_set('zend.int_string_max_digits', '0');
$big = new BcMath\Number(10 ** 5000);
var_dump(strlen((string) $big));

?>
--EXPECT--
string(31) "1267650600228229401496703205376"
int(0)
string(32) "-1267650600228229401496703205376"
bool(true)
Integer too large to convert to string; it exceeds the limit of 4300 digits, configurable via the zend.int_string_max_digits setting
int(5001)

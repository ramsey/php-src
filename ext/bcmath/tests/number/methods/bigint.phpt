--TEST--
BcMath\Number methods accept a bigint argument
--EXTENSIONS--
bcmath
--FILE--
<?php

$big = 2 ** 100;
$s = '1267650600228229401496703205376';
$a = new BcMath\Number('5');

var_dump((string) $a->add($big) === (string) $a->add($s));
var_dump((string) $a->sub($big) === (string) $a->sub($s));
var_dump((string) $a->mul($big) === (string) $a->mul($s));
var_dump((string) $a->div($big) === (string) $a->div($s));
var_dump((string) $a->mod($big) === (string) $a->mod($s));
var_dump((string) $a->powmod($big, 7) === (string) $a->powmod($s, 7));
var_dump(($a->compare($big)) === ($a->compare($s)));

[$q1, $r1] = $a->divmod($big);
[$q2, $r2] = $a->divmod($s);
var_dump((string) $q1 === (string) $q2 && (string) $r1 === (string) $r2);

var_dump((string) $a->add($big));

$eBig = null;
$eStr = null;

try {
    $a->pow($big);
} catch (ValueError $e) {
    $eBig = $e->getMessage();
}

try {
    $a->pow($s);
} catch (ValueError $e) {
    $eStr = $e->getMessage();
}

var_dump($eBig !== null && $eBig === $eStr);

try {
    $a->add(10 ** 5000);
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
string(31) "1267650600228229401496703205381"
bool(true)
Integer too large to convert to string; it exceeds the limit of 4300 digits, configurable via the zend.int_string_max_digits setting

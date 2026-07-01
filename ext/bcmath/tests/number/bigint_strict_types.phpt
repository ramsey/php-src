--TEST--
BcMath\Number accepts a bigint under strict_types=1
--EXTENSIONS--
bcmath
--FILE--
<?php

declare(strict_types=1);

$big = 2 ** 100;
$s = '1267650600228229401496703205376';

var_dump((string) new BcMath\Number($big) === $s);

$a = new BcMath\Number('5');
var_dump((string) ($a + $big) === (string) ($a + $s));
var_dump((string) $a->add($big) === (string) $a->add($s));
var_dump($a->compare($big) === $a->compare($s));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)

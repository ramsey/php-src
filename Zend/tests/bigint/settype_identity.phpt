--TEST--
Bigint: settype($x, 'int') and settype($x, 'integer') are identities on bigints
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php

$big = 2 ** 100;

// 'int' alias
$x = $big;
$ret = settype($x, 'int');
var_dump($ret);
var_dump($x);

// 'integer' alias
$y = $big;
$ret2 = settype($y, 'integer');
var_dump($ret2);
var_dump($y);

// Pin: settype to 'float' still converts bigint to float
$z = 2 ** 100;
settype($z, 'float');
var_dump(is_float($z));

// Pin: settype to 'string' still converts bigint to string
$s = 2 ** 100;
settype($s, 'string');
var_dump($s);

// settype to 'bool'; nonzero bigint == true
$b1 = 2 ** 100;
settype($b1, 'bool');
var_dump($b1);

// settype to 'boolean'; nonzero bigint == true
$b2 = 2 ** 100;
settype($b2, 'boolean');
var_dump($b2);

// settype to 'bool'; zero bigint == false
$b3 = zend_test_make_bigint('0');
settype($b3, 'bool');
var_dump($b3);

?>
--EXPECT--
bool(true)
int(1267650600228229401496703205376)
bool(true)
int(1267650600228229401496703205376)
bool(true)
string(31) "1267650600228229401496703205376"
bool(true)
bool(true)
bool(false)

--TEST--
bigint: (int) cast and settype('integer') are identities on an already-boxed integer
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$cast = (int) $pos;
var_dump(zend_test_int_is_boxed($cast));
var_dump(zend_test_bigint_to_string($cast) === '340282366920938463463374607431768211456');

$expr = (int) zend_test_bigint_make('-340282366920938463463374607431768211456');
var_dump(zend_test_int_is_boxed($expr));
var_dump(zend_test_bigint_to_string($expr) === '-340282366920938463463374607431768211456');

$small = (int) zend_test_bigint_make('5');
var_dump($small);

$var = zend_test_bigint_make('170141183460469231731687303715884105728');
settype($var, 'integer');
var_dump(zend_test_int_is_boxed($var));
var_dump(zend_test_bigint_to_string($var) === '170141183460469231731687303715884105728');

$var2 = zend_test_bigint_make('-170141183460469231731687303715884105728');
settype($var2, 'int');
var_dump(zend_test_int_is_boxed($var2));
var_dump(zend_test_bigint_to_string($var2) === '-170141183460469231731687303715884105728');
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
int(5)
bool(true)
bool(true)
bool(true)
bool(true)

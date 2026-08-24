--TEST--
bigint: compile-time (int) folding is exact and keeps a boxed literal boxed
--EXTENSIONS--
zend_test
--FILE--
<?php
const A = (int) 1e20;
const B = (int) 99999999999999999999;
const C = (int) '99999999999999999999';
const D = (int) -2.5e20;
const E = (int) '1e20';
const F = (int) 2.9;

var_dump(A);
var_dump(zend_test_int_is_boxed(A));
var_dump(B);
var_dump(zend_test_int_is_boxed(B));
var_dump(C);
var_dump(zend_test_int_is_boxed(C));
var_dump(D);
var_dump(E);
var_dump(F);

const BIG = 1e20;
const BIGSTR = '99999999999999999999';
class K {
    const FROM_FLOAT = (int) BIG;
    const FROM_STRING = (int) BIGSTR;
    const FROM_BOX = (int) (2 ** 100);
}
var_dump(K::FROM_FLOAT);
var_dump(zend_test_int_is_boxed(K::FROM_FLOAT));
var_dump(K::FROM_STRING);
var_dump(K::FROM_BOX);
var_dump(K::FROM_BOX === 2 ** 100);

function def(int $x = (int) BIG): int {
    return $x;
}
var_dump(def());

var_dump((int) 1e20 === 10 ** 20);
var_dump((int) 99999999999999999999 === 10 ** 20 - 1);

const N = (int) NAN;
var_dump(N);
const I = (int) INF;
var_dump(I);
?>
--EXPECTF--
int(100000000000000000000)
bool(true)
int(99999999999999999999)
bool(true)
int(99999999999999999999)
bool(true)
int(-250000000000000000000)
int(100000000000000000000)
int(2)
int(100000000000000000000)
bool(true)
int(99999999999999999999)
int(1267650600228229401496703205376)
bool(true)
int(100000000000000000000)
bool(true)
bool(true)

Warning: The float NAN is not representable as an int, cast occurred in %s on line %d
int(0)

Warning: The float INF is not representable as an int, cast occurred in %s on line %d
int(0)

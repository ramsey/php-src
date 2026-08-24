--TEST--
bigint: (int), settype, and intval truncate finite doubles and numeric strings exactly
--EXTENSIONS--
zend_test
--FILE--
<?php
function cast(string $label, mixed $value): void {
    $r = (int) $value;
    echo '(int) ' . $label . ': ';
    var_dump($r);
    $s = $value;
    settype($s, 'int');
    echo 'settype ' . $label . ': ';
    var_dump($s);
    echo 'intval ' . $label . ': ';
    var_dump(intval($value));
}

cast('1e20', 1e20);
cast('-2.5e20', -2.5e20);
cast('2.9', 2.9);
cast('2.0 ** 64 + 0.5', 2.0 ** 64 + 0.5);
cast("'99999999999999999999'", '99999999999999999999');
cast("'-99999999999999999999'", '-99999999999999999999');
cast("'1e20'", '1e20');
cast("'9223372036854775808.5'", '9223372036854775808.5');
cast("' 42 '", ' 42 ');
cast("'12abc'", '12abc');
cast("'abc'", 'abc');
cast("'1e999'", '1e999');
cast('2 ** 100', 2 ** 100);

var_dump(zend_test_int_is_boxed((int) 1e20));
var_dump((int) 1e20 === 10 ** 20);
var_dump((int) '1e20' === 10 ** 20);
var_dump((int) '99999999999999999999' === 10 ** 20 - 1);
var_dump((int) (2.0 ** 64 + 0.5) === 2 ** 64);
var_dump((int) (2 ** 100) === 2 ** 100);
var_dump(intval(-2.5e20) === -25 * 10 ** 19);

cast('INF', INF);
cast('-INF', -INF);
cast('NAN', NAN);

var_dump(intval('99999999999999999999', 10));
var_dump(intval('99999999999999999999', 16));
var_dump(intval('1e20', 10));
?>
--EXPECTF--
(int) 1e20: int(100000000000000000000)
settype 1e20: int(100000000000000000000)
intval 1e20: int(100000000000000000000)
(int) -2.5e20: int(-250000000000000000000)
settype -2.5e20: int(-250000000000000000000)
intval -2.5e20: int(-250000000000000000000)
(int) 2.9: int(2)
settype 2.9: int(2)
intval 2.9: int(2)
(int) 2.0 ** 64 + 0.5: int(18446744073709551616)
settype 2.0 ** 64 + 0.5: int(18446744073709551616)
intval 2.0 ** 64 + 0.5: int(18446744073709551616)
(int) '99999999999999999999': int(99999999999999999999)
settype '99999999999999999999': int(99999999999999999999)
intval '99999999999999999999': int(99999999999999999999)
(int) '-99999999999999999999': int(-99999999999999999999)
settype '-99999999999999999999': int(-99999999999999999999)
intval '-99999999999999999999': int(-99999999999999999999)
(int) '1e20': int(100000000000000000000)
settype '1e20': int(100000000000000000000)
intval '1e20': int(100000000000000000000)
(int) '9223372036854775808.5': int(9223372036854775808)
settype '9223372036854775808.5': int(9223372036854775808)
intval '9223372036854775808.5': int(9223372036854775808)
(int) ' 42 ': int(42)
settype ' 42 ': int(42)
intval ' 42 ': int(42)
(int) '12abc': int(12)
settype '12abc': int(12)
intval '12abc': int(12)
(int) 'abc': int(0)
settype 'abc': int(0)
intval 'abc': int(0)
(int) '1e999': int(0)
settype '1e999': int(0)
intval '1e999': int(0)
(int) 2 ** 100: int(1267650600228229401496703205376)
settype 2 ** 100: int(1267650600228229401496703205376)
intval 2 ** 100: int(1267650600228229401496703205376)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

Warning: The float INF is not representable as an int, cast occurred in %s on line %d
(int) INF: int(0)

Warning: The float INF is not representable as an int, cast occurred in %s on line %d
settype INF: int(0)
intval INF: 
Warning: The float INF is not representable as an int, cast occurred in %s on line %d
int(0)

Warning: The float -INF is not representable as an int, cast occurred in %s on line %d
(int) -INF: int(0)

Warning: The float -INF is not representable as an int, cast occurred in %s on line %d
settype -INF: int(0)
intval -INF: 
Warning: The float -INF is not representable as an int, cast occurred in %s on line %d
int(0)

Warning: The float NAN is not representable as an int, cast occurred in %s on line %d
(int) NAN: int(0)

Warning: The float NAN is not representable as an int, cast occurred in %s on line %d
settype NAN: int(0)
intval NAN: 
Warning: The float NAN is not representable as an int, cast occurred in %s on line %d
int(0)
int(9223372036854775807)
int(9223372036854775807)
int(9223372036854775807)

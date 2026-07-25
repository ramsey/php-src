--TEST--
bigint: comparison operators against box, long, double, and string
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$a = 100000000000000000000;
$b = 100000000000000000001;
$negA = -100000000000000000000;
$negB = -100000000000000000001;

check('$a == $a', $a == $a);
check('$a == $b', $a == $b);
check('$a < $b', $a < $b);
check('$b > $a', $b > $a);
check('$a <=> $b', $a <=> $b);
check('$b <=> $a', $b <=> $a);
check('$a <=> $a', $a <=> $a);
check('$negA <=> $negB', $negA <=> $negB);
check('$negB <=> $negA', $negB <=> $negA);
check('$negA < $a', $negA < $a);

check('$a > PHP_INT_MAX', $a > PHP_INT_MAX);
check('PHP_INT_MAX < $a', PHP_INT_MAX < $a);
check('$negA < PHP_INT_MIN', $negA < PHP_INT_MIN);
check('PHP_INT_MIN > $negA', PHP_INT_MIN > $negA);
check('$a == PHP_INT_MAX', $a == PHP_INT_MAX);

$box128 = 340282366920938463463374607431768211456;
check('$box128 == 2.0 ** 128', $box128 == 2.0 ** 128);
check('2.0 ** 128 == $box128', 2.0 ** 128 == $box128);
check('$box128 <=> 2.0 ** 128', $box128 <=> 2.0 ** 128);
check('2.0 ** 128 <=> $box128', 2.0 ** 128 <=> $box128);
check('$box128 < INF', $box128 < INF);
check('$box128 > -INF', $box128 > -INF);
check('$box128 == INF', $box128 == INF);
check('$box128 == NAN', $box128 == NAN);
check('$box128 < NAN', $box128 < NAN);
check('$box128 > NAN', $box128 > NAN);

check('$a > \'5\'', $a > '5');
check('\'5\' < $a', '5' < $a);
check('$a == \'100000000000000000000\'', $a == '100000000000000000000');
check('\'100000000000000000000\' == $a', '100000000000000000000' == $a);
check('$a == \'not-a-number\'', $a == 'not-a-number');
check('$a > \'not-a-number\'', $a > 'not-a-number');
check('\'not-a-number\' > $a', 'not-a-number' > $a);
?>
--EXPECT--
$a == $a: bool(true)
$a == $b: bool(false)
$a < $b: bool(true)
$b > $a: bool(true)
$a <=> $b: int(-1)
$b <=> $a: int(1)
$a <=> $a: int(0)
$negA <=> $negB: int(1)
$negB <=> $negA: int(-1)
$negA < $a: bool(true)
$a > PHP_INT_MAX: bool(true)
PHP_INT_MAX < $a: bool(true)
$negA < PHP_INT_MIN: bool(true)
PHP_INT_MIN > $negA: bool(true)
$a == PHP_INT_MAX: bool(false)
$box128 == 2.0 ** 128: bool(true)
2.0 ** 128 == $box128: bool(true)
$box128 <=> 2.0 ** 128: int(0)
2.0 ** 128 <=> $box128: int(0)
$box128 < INF: bool(true)
$box128 > -INF: bool(true)
$box128 == INF: bool(false)
$box128 == NAN: bool(false)
$box128 < NAN: bool(false)
$box128 > NAN: bool(false)
$a > '5': bool(true)
'5' < $a: bool(true)
$a == '100000000000000000000': bool(true)
'100000000000000000000' == $a: bool(true)
$a == 'not-a-number': bool(false)
$a > 'not-a-number': bool(false)
'not-a-number' > $a: bool(true)

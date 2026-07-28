--TEST--
bigint: exact int-vs-float comparison
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

check('9223372036854775807 == 9.2233720368547758E+18', 9223372036854775807 == 9.2233720368547758E+18);
check('PHP_INT_MAX == (float) PHP_INT_MAX', PHP_INT_MAX == (float) PHP_INT_MAX);
check('PHP_INT_MAX < (float) PHP_INT_MAX', PHP_INT_MAX < (float) PHP_INT_MAX);
check('2 ** 64 == 2.0 ** 64', 2 ** 64 == 2.0 ** 64);
check('2 ** 64 + 1 == 2.0 ** 64', 2 ** 64 + 1 == 2.0 ** 64);
check('2 ** 64 + 1 > 2.0 ** 64', 2 ** 64 + 1 > 2.0 ** 64);
check('2 ** 10000 == INF', 2 ** 10000 == INF);
check('2 ** 10000 < INF', 2 ** 10000 < INF);
check('-(2 ** 10000) > -INF', -(2 ** 10000) > -INF);
check('1 <=> NAN', 1 <=> NAN);
check('NAN <=> 1', NAN <=> 1);
check('2 ** 100 <=> NAN', 2 ** 100 <=> NAN);
check('2 ** 100 == NAN', 2 ** 100 == NAN);
check('0 == -0.0', 0 == -0.0);
check('2 ** 64 <=> -0.0', 2 ** 64 <=> -0.0);

$pairs = [
    'PHP_INT_MAX <=> (float) PHP_INT_MAX' => [PHP_INT_MAX, (float) PHP_INT_MAX],
    '2 ** 64 <=> 2.0 ** 64' => [2 ** 64, 2.0 ** 64],
    '2 ** 64 + 1 <=> 2.0 ** 64' => [2 ** 64 + 1, 2.0 ** 64],
    '2 ** 10000 <=> INF' => [2 ** 10000, INF],
    '-(2 ** 10000) <=> -INF' => [-(2 ** 10000), -INF],
    '2 ** 64 <=> -0.0' => [2 ** 64, -0.0],
];

foreach ($pairs as $label => [$a, $b]) {
    check('symmetry: ' . $label, ($a <=> $b) === -($b <=> $a));
}
?>
--EXPECT--
9223372036854775807 == 9.2233720368547758E+18: bool(false)
PHP_INT_MAX == (float) PHP_INT_MAX: bool(true)
PHP_INT_MAX < (float) PHP_INT_MAX: bool(false)
2 ** 64 == 2.0 ** 64: bool(true)
2 ** 64 + 1 == 2.0 ** 64: bool(false)
2 ** 64 + 1 > 2.0 ** 64: bool(true)
2 ** 10000 == INF: bool(false)
2 ** 10000 < INF: bool(true)
-(2 ** 10000) > -INF: bool(true)
1 <=> NAN: int(1)
NAN <=> 1: int(1)
2 ** 100 <=> NAN: int(1)
2 ** 100 == NAN: bool(false)
0 == -0.0: bool(true)
2 ** 64 <=> -0.0: int(1)
symmetry: PHP_INT_MAX <=> (float) PHP_INT_MAX: bool(true)
symmetry: 2 ** 64 <=> 2.0 ** 64: bool(true)
symmetry: 2 ** 64 + 1 <=> 2.0 ** 64: bool(true)
symmetry: 2 ** 10000 <=> INF: bool(true)
symmetry: -(2 ** 10000) <=> -INF: bool(true)
symmetry: 2 ** 64 <=> -0.0: bool(true)

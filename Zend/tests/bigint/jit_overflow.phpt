--TEST--
bigint: tracing jit side-exits on integer overflow
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=32M
opcache.jit_hot_loop=1
--FILE--
<?php
function check(string $label, bool $ok): void {
    echo $label . ': ' . ($ok ? 'ok' : 'FAIL') . "\n";
}

$i = PHP_INT_MAX - 4;
$seen = [];
for ($n = 0; $n < 10; $n++) {
    $i++;
    $seen[$n] = $i;
}
check('inc crossing is_int', is_int($seen[4]) && !is_float($seen[4]));
check('inc crossing exact', $seen[4] - PHP_INT_MAX === 1);
check('inc continues exact', $seen[9] - PHP_INT_MAX === 6);
check('inc last is_int', is_int($seen[9]));

$m = 1;
$dbl = [];
for ($n = 0; $n < 70; $n++) {
    $m *= 2;
    $dbl[$n] = $m;
}
check('mul crossing is_int', is_int($dbl[62]) && !is_float($dbl[62]));
check('mul exact digits', (string) $dbl[62] === '9223372036854775808');
check('mul doubles back', $dbl[61] * 2 === $dbl[62]);

$sum = 0;
for ($n = 0; $n < 100000; $n++) {
    $sum = PHP_INT_MAX + $n;
}
check('add crossing is_int', is_int($sum));
check('add exact', $sum - PHP_INT_MAX === 99999);
?>
--EXPECT--
inc crossing is_int: ok
inc crossing exact: ok
inc continues exact: ok
inc last is_int: ok
mul crossing is_int: ok
mul exact digits: ok
mul doubles back: ok
add crossing is_int: ok
add exact: ok

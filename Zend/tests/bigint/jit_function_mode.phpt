--TEST--
bigint: function jit calls the boxing helper on integer overflow
--INI--
opcache.enable_cli=1
opcache.jit=function
opcache.jit_buffer_size=32M
opcache.jit_hot_func=1
--FILE--
<?php
function check(string $label, bool $ok): void {
    echo $label . ': ' . ($ok ? 'ok' : 'FAIL') . "\n";
}

function inc_across(int $start, int $steps): int {
    $i = $start;
    for ($k = 0; $k < $steps; $k++) {
        $i++;
    }
    return $i;
}

function mul_across(int $base, int $steps): int {
    $m = $base;
    for ($k = 0; $k < $steps; $k++) {
        $m *= 2;
    }
    return $m;
}

function add_to_max(int $n): int {
    return PHP_INT_MAX + $n;
}

$r = 0;
for ($n = 0; $n < 100000; $n++) {
    $r = inc_across(PHP_INT_MAX - 4, 6);
}
check('fn inc crossing is_int', is_int($r) && !is_float($r));
check('fn inc exact', $r - PHP_INT_MAX === 2);

$r = 0;
for ($n = 0; $n < 100000; $n++) {
    $r = mul_across(1, 63);
}
check('fn mul crossing is_int', is_int($r) && !is_float($r));
check('fn mul exact digits', (string) $r === '9223372036854775808');

$r = 0;
for ($n = 0; $n < 100000; $n++) {
    $r = add_to_max(7);
}
check('fn add crossing is_int', is_int($r));
check('fn add exact', $r - PHP_INT_MAX === 7);
?>
--EXPECT--
fn inc crossing is_int: ok
fn inc exact: ok
fn mul crossing is_int: ok
fn mul exact digits: ok
fn add crossing is_int: ok
fn add exact: ok

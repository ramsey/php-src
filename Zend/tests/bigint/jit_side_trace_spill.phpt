--TEST--
bigint: tracing jit side trace stores dirty parent registers at entry
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=64M
opcache.jit_hot_loop=1
opcache.jit_hot_func=1
opcache.jit_hot_return=1
opcache.jit_hot_side_exit=1
--FILE--
<?php
function pre_dec(int $stop): int {
    $a = PHP_INT_MIN + 10;
    $b = 0;
    while ($b++ < 3) {
        --$a;
        if ($b === $stop) {
            return $a;
        }
        $a = (int) ($b + PHP_INT_MAX + 2);
    }
    return $a;
}

$r = pre_dec(5);
echo 'int: ' . (is_int($r) ? 'ok' : 'FAIL') . "\n";
echo 'exact: ' . ($r - PHP_INT_MAX === 5 ? 'ok' : 'FAIL') . "\n";
?>
--EXPECT--
int: ok
exact: ok

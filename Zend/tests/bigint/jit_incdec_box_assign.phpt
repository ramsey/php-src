--TEST--
bigint: tracing jit frees the old box when assign overwrites an inc/dec result
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
function post_dec(): int {
    $a = PHP_INT_MIN + 1;
    $b = 0;
    while ($b++ < 3) {
        $a = (int) ($a-- - $b - 1);
    }
    return $a;
}

function pre_inc(): int {
    $a = PHP_INT_MAX - 1;
    $b = 0;
    while ($b++ < 3) {
        $a = (int) (++$a + $b + 1);
    }
    return $a;
}

$d = post_dec();
echo 'post_dec int: ' . (is_int($d) ? 'ok' : 'FAIL') . "\n";
echo 'post_dec exact: ' . ($d - PHP_INT_MIN === -8 ? 'ok' : 'FAIL') . "\n";
$i = pre_inc();
echo 'pre_inc int: ' . (is_int($i) ? 'ok' : 'FAIL') . "\n";
echo 'pre_inc exact: ' . ($i - PHP_INT_MAX === 11 ? 'ok' : 'FAIL') . "\n";
?>
--EXPECT--
post_dec int: ok
post_dec exact: ok
pre_inc int: ok
pre_inc exact: ok

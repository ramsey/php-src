--TEST--
bigint: function jit frees the boxed elements of a local array
--INI--
opcache.enable_cli=1
opcache.jit=function
opcache.jit_buffer_size=64M
opcache.jit_hot_func=1
--FILE--
<?php
function fill(int $n): int {
    $a = [];
    for ($i = 0; $i < $n; $i++) {
        $a[] = PHP_INT_MAX + $i;
    }
    return $a[$n - 1];
}

$r = 0;
for ($k = 0; $k < 20; $k++) {
    $r = fill(8);
}
echo 'is_int: ' . (is_int($r) ? 'ok' : 'FAIL') . "\n";
echo 'exact: ' . ($r - PHP_INT_MAX === 7 ? 'ok' : 'FAIL') . "\n";
?>
--EXPECT--
is_int: ok
exact: ok

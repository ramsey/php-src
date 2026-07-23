--TEST--
bigint: tracing JIT stays correct on widened int-typed args
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=16M
opcache.jit_hot_func=1
--FILE--
<?php
function add_one(int $x): int {
    return $x + 1;
}

function double_it(int $x): int {
    return $x * 2;
}

$sum = 0;
for ($i = 0; $i < 100000; $i++) {
    $sum += add_one($i);
    $sum += double_it($i);
}
echo $sum . "\n";
?>
--EXPECT--
14999950000

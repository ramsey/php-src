--TEST--
bigint: (int) results that box stay boxed in arithmetic under tracing JIT
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=16M
opcache.jit_hot_func=2
opcache.jit_hot_loop=2
--FILE--
<?php
function fromFloat(float $d): int {
    $r = (int) $d;
    return $r + 1;
}

function fromString(string $s): int {
    $r = (int) $s;
    return $r * 2;
}

for ($i = 0; $i < 100; $i++) {
    fromFloat(1.5);
    fromString('7');
}

var_dump(fromFloat(1e20));
var_dump(fromString('99999999999999999999'));
var_dump(fromFloat(1e20) === 10 ** 20 + 1);
?>
--EXPECT--
int(100000000000000000001)
int(199999999999999999998)
bool(true)

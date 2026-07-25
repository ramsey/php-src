--TEST--
bigint: boxed by-value argument to an internal call is freed under tracing jit
--EXTENSIONS--
gmp
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=32M
opcache.jit_hot_loop=1
--FILE--
<?php
$last = null;
for ($n = 0; $n < 100000; $n++) {
    $last = gmp_init(PHP_INT_MAX + $n);
}
echo (gmp_strval($last) === (string) (PHP_INT_MAX + 99999) ? 'exact' : 'FAIL') . "\n";
?>
--EXPECT--
exact

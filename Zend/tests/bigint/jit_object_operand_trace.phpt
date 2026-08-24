--TEST--
bigint: tracing jit compiles a trace whose operator handler returns a string
--EXTENSIONS--
zend_test
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
$big = 2 ** 100;
$obj = new _ZendTestBigintOperand();
$add = static fn ($o, $b) => $o + $b;
for ($i = 0; $i < 5; $i++) {
    var_dump($add($obj, $big));
}
?>
--EXPECT--
string(6) "marker"
string(6) "marker"
string(6) "marker"
string(6) "marker"
string(6) "marker"

--TEST--
bigint: boxed builtin returns keep their destructor under JIT
--EXTENSIONS--
opcache
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=32M
opcache.jit_hot_loop=1
--FILE--
<?php
$arr = [9223372036854775808 => 1];
function probe(array $a) {
    $hits = 0;
    for ($i = 0; $i < 200; $i++) {
        $v = array_key_first($a);
        if (is_int($v)) {
            $hits++;
        }
    }
    return $hits;
}
var_dump(probe($arr));
echo 'done';
?>
--EXPECT--
int(200)
done

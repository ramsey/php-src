--TEST--
bigint: array dim JIT helpers accept boxed and float keys under tracing
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=16M
opcache.jit_hot_loop=1
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$bigKey = 2 ** 100;

$a = [$bigKey => 'read-val'];
for ($i = 0; $i < 200; $i++) {
    $read = $a[$bigKey];
}
check('read with boxed key', $read);

$b = [$bigKey => 'isset-val'];
for ($i = 0; $i < 200; $i++) {
    $isset = isset($b[$bigKey]);
}
check('isset with boxed key', $isset);

$c = [$bigKey => 'coalesce-val'];
for ($i = 0; $i < 200; $i++) {
    $coalesce = $c[$bigKey] ?? 'missing';
}
check('coalesce with boxed key', $coalesce);

$d = [];
for ($i = 0; $i < 200; $i++) {
    $d[$bigKey] = $i;
}
check('write assign with boxed key', $d[$bigKey]);

$e = [$bigKey => ''];
for ($i = 0; $i < 200; $i++) {
    $e[$bigKey] .= 'x';
}
check('compound assign length with boxed key', strlen($e[$bigKey]));

$f = [$bigKey => 'to-unset'];
for ($i = 0; $i < 200; $i++) {
    unset($f[$bigKey]);
}
check('unset with boxed key removes it', array_key_exists($bigKey, $f));

$g = [];
for ($i = 0; $i < 200; $i++) {
    $g[1e20] = $i;
}
check('float key row (huge integral float)', $g[1e20]);
?>
--EXPECT--
read with boxed key: string(8) "read-val"
isset with boxed key: bool(true)
coalesce with boxed key: string(12) "coalesce-val"
write assign with boxed key: int(199)
compound assign length with boxed key: int(200)
unset with boxed key removes it: bool(false)
float key row (huge integral float): int(199)

--TEST--
bigint: string offset JIT helpers accept offsets outside int range under tracing
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=16M
opcache.jit_hot_loop=1
opcache.jit_hot_func=1
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$s = 'abc';
$pos = 2 ** 100;
$neg = -(2 ** 100);

for ($i = 0; $i < 200; $i++) {
    $read = @$s[$pos];
}
check('read with a boxed offset', $read);

for ($i = 0; $i < 200; $i++) {
    $negRead = @$s[$neg];
}
check('read before the start with a boxed offset', $negRead);

for ($i = 0; $i < 200; $i++) {
    $isset = isset($s[$pos]);
}
check('isset with a boxed offset', $isset);

for ($i = 0; $i < 200; $i++) {
    $coalesce = $s[$pos] ?? 'fallback';
}
check('coalesce with a boxed offset', $coalesce);

for ($i = 0; $i < 200; $i++) {
    $strDim = @$s['99999999999999999999'];
}
check('read with a big numeric string offset', $strDim);

$write = 'abc';
for ($i = 0; $i < 200; $i++) {
    @$write[$neg] = 'x';
}
check('write before the start leaves the string alone', $write);

// The diagnostics below are collected instead of displayed, so that the loop
// body stays the one shape the compiled trace runs 200 times.
$raised = [];

set_error_handler(function (int $errno, string $errstr) use (&$raised): bool {
    $raised[$errstr] = ($raised[$errstr] ?? 0) + 1;

    return true;
}, E_WARNING);

for ($i = 0; $i < 200; $i++) {
    $s[$pos];
}
check('one text for the boxed read', count($raised));
check('boxed read text', array_key_first($raised));
check('boxed read count', reset($raised));

$raised = [];
for ($i = 0; $i < 200; $i++) {
    $s['1267650600228229401496703205376'];
}
check('one text for the string dim read', count($raised));
check('string dim read text', array_key_first($raised));
check('string dim read count', reset($raised));

$raised = [];
$rejected = 'abc';
for ($i = 0; $i < 200; $i++) {
    $rejected[$neg] = 'x';
}
check('one text for the negative write', count($raised));
check('negative write text', array_key_first($raised));
check('negative write count', reset($raised));
check('string unchanged by the rejected writes', $rejected);

restore_error_handler();
?>
--EXPECT--
read with a boxed offset: string(0) ""
read before the start with a boxed offset: string(0) ""
isset with a boxed offset: bool(false)
coalesce with a boxed offset: string(8) "fallback"
read with a big numeric string offset: string(0) ""
write before the start leaves the string alone: string(3) "abc"
one text for the boxed read: int(1)
boxed read text: string(59) "Uninitialized string offset 1267650600228229401496703205376"
boxed read count: int(200)
one text for the string dim read: int(1)
string dim read text: string(59) "Uninitialized string offset 1267650600228229401496703205376"
string dim read count: int(200)
one text for the negative write: int(1)
negative write text: string(54) "Illegal string offset -1267650600228229401496703205376"
negative write count: int(200)
string unchanged by the rejected writes: string(3) "abc"

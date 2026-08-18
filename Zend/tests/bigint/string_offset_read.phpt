--TEST--
bigint: reading a string offset outside int range reports the offset that was written
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$s = 'abc';
$pos = 2 ** 100;
$neg = -(2 ** 100);

check('read past the end', $s[$pos]);
check('read before the start', $s[$neg]);
check('coalesce past the end', $s[$pos] ?? 'fallback');
check('coalesce before the start', $s[$neg] ?? 'fallback');
check('isset past the end', isset($s[$pos]));
check('isset before the start', isset($s[$neg]));
check('empty past the end', empty($s[$pos]));
check('empty before the start', empty($s[$neg]));

$boxed = $pos;
$alias = &$boxed;
check('read through a reference', $s[$alias]);

check('interpolated', "{$s[$pos]}");

set_error_handler(function (int $errno, string $errstr): bool {
    throw new Exception($errstr);
}, E_WARNING);

try {
    $s[$pos];
} catch (Exception $e) {
    check('warning promoted to exception', $e::class . ': ' . $e->getMessage());
}

restore_error_handler();
?>
--EXPECTF--
Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
read past the end: string(0) ""

Warning: Uninitialized string offset -1267650600228229401496703205376 in %s on line %d
read before the start: string(0) ""
coalesce past the end: string(8) "fallback"
coalesce before the start: string(8) "fallback"
isset past the end: bool(false)
isset before the start: bool(false)
empty past the end: bool(true)
empty before the start: bool(true)

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
read through a reference: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
interpolated: string(0) ""
warning promoted to exception: string(70) "Exception: Uninitialized string offset 1267650600228229401496703205376"

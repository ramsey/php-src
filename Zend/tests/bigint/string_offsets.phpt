--TEST--
bigint: string offsets with a boxed integer: isset/empty saturate out of range, direct read still rejects the type
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$neg = zend_test_bigint_make('-340282366920938463463374607431768211456');
$s = 'abc';

var_dump(isset($s[$pos]));
var_dump(isset($s[$neg]));
var_dump(empty($s[$pos]));

try {
    $s[$pos];
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    $s[$pos] ?? 'none';
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
bool(false)
bool(false)
bool(true)
TypeError: Cannot access offset of type int on string
TypeError: Cannot access offset of type int on string

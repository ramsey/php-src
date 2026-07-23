--TEST--
var_dump, debug_zval_dump, and print_r render boxed integers as exact decimal
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$neg = zend_test_bigint_make('-340282366920938463463374607431768211456');
$long = 42;

var_dump($pos);
var_dump($neg);
debug_zval_dump($pos);
debug_zval_dump($neg);
debug_zval_dump($long);
echo print_r($pos, true) . "\n";
echo print_r($neg, true) . "\n";
print_r(['a' => $pos, 'b' => $neg]);
?>
--EXPECT--
int(340282366920938463463374607431768211456)
int(-340282366920938463463374607431768211456)
int(340282366920938463463374607431768211456) bigint refcount(2)
int(-340282366920938463463374607431768211456) bigint refcount(2)
int(42)
340282366920938463463374607431768211456
-340282366920938463463374607431768211456
Array
(
    [a] => 340282366920938463463374607431768211456
    [b] => -340282366920938463463374607431768211456
)

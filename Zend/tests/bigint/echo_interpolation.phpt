--TEST--
echo and double-quoted string interpolation render boxed integers as exact decimal
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$neg = zend_test_bigint_make('-340282366920938463463374607431768211456');

echo $pos . "\n";
echo $neg . "\n";
echo "value is $pos\n";
echo "value is {$neg}\n";
?>
--EXPECT--
340282366920938463463374607431768211456
-340282366920938463463374607431768211456
value is 340282366920938463463374607431768211456
value is -340282366920938463463374607431768211456

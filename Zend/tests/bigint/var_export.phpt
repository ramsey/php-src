--TEST--
var_export renders boxed integers as exact decimal
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$neg = zend_test_bigint_make('-340282366920938463463374607431768211456');

var_export($pos);
echo "\n";
var_export($neg);
echo "\n";
var_export(['a' => $pos, 'b' => $neg]);
echo "\n";
?>
--EXPECT--
340282366920938463463374607431768211456
-340282366920938463463374607431768211456
array (
  'a' => 340282366920938463463374607431768211456,
  'b' => -340282366920938463463374607431768211456,
)

--TEST--
bigint: json_encode renders boxed integers as exact decimal
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = zend_test_bigint_make('340282366920938463463374607431768211456');
$neg = zend_test_bigint_make('-340282366920938463463374607431768211456');

var_dump(json_encode($pos));
var_dump(json_encode($neg));
var_dump(json_encode(['a' => $pos, 'b' => $neg]));
?>
--EXPECT--
string(39) "340282366920938463463374607431768211456"
string(40) "-340282366920938463463374607431768211456"
string(90) "{"a":340282366920938463463374607431768211456,"b":-340282366920938463463374607431768211456}"

--TEST--
Bigint: in-place string conversion
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--INI--
opcache.enable_cli=0
--FILE--
<?php
// settype() routes through the in-place _convert_to_string:
$x = PHP_INT_MAX + 1;
settype($x, 'string');
var_dump($x);

$y = PHP_INT_MIN - 1;
settype($y, 'string');
var_dump($y);

// compile-time constant folding of concat with a bigint literal:
echo 'value=' . 2147483648 . "\n";
?>
--EXPECT--
string(10) "2147483648"
string(11) "-2147483649"
value=2147483648

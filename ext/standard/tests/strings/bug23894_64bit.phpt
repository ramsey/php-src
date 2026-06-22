--TEST--
Bug #23894 (sprintf() decimal specifiers problem) 64bit version
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only");
?>
--FILE--
<?php
$a = -12.3456;
$test = sprintf("%04d", $a);
var_dump($test, bin2hex($test));
$test = sprintf("% 13u", $a);
var_dump($test, bin2hex($test));
?>
--EXPECT--
string(4) "-012"
string(8) "2d303132"
string(13) "          -12"
string(26) "202020202020202020202d3132"

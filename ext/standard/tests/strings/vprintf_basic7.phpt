--TEST--
Test vprintf() function : basic functionality - unsigned format
--SKIPIF--
<?php
if (PHP_INT_SIZE != 4) die("skip this test is for 32bit platform only");
?>
--FILE--
<?php
echo "*** Testing vprintf() : basic functionality - using unsigned format ***\n";

// Initialise all required variables
$format = "format";
$format1 = "%u";
$format2 = "%u %u";
$format3 = "%u %u %u";
$arg1 = array(-1111);
$arg2 = array(-1111,-1234567);
$arg3 = array(-1111,-1234567,-2345432);

$result = vprintf($format1,$arg1);
echo "\n";
var_dump($result);

$result = vprintf($format2,$arg2);
echo "\n";
var_dump($result);

$result = vprintf($format3,$arg3);
echo "\n";
var_dump($result);

?>
--EXPECT--
*** Testing vprintf() : basic functionality - using unsigned format ***
-1111
int(5)
-1111 -1234567
int(14)
-1111 -1234567 -2345432
int(23)

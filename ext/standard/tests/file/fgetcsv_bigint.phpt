--TEST--
fgetcsv(): a big-integer $length reads like an in-range long or is rejected like an out-of-range one
--EXTENSIONS--
zend_test
--FILE--
<?php
$file = __DIR__ . '/fgetcsv_bigint.csv';
file_put_contents($file, "a,b,c\n");

// In-range bigint length behaves like the same long.
$fp = fopen($file, 'r');
var_dump(fgetcsv($fp, zend_test_make_bigint('100'), ',', '"', ''));
fclose($fp);

// Positive out-of-range big integer: rejected like a too-large long.
$fp = fopen($file, 'r');
try {
    fgetcsv($fp, 2 ** 100, ',', '"', '');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
fclose($fp);

// Negative out-of-range big integer: rejected like a negative long.
$fp = fopen($file, 'r');
try {
    fgetcsv($fp, -(2 ** 100), ',', '"', '');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
fclose($fp);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/fgetcsv_bigint.csv');
?>
--EXPECTF--
array(3) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
}
fgetcsv(): Argument #2 ($length) must be between 0 and %d
fgetcsv(): Argument #2 ($length) must be between 0 and %d

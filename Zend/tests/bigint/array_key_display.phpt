--TEST--
Bigint: var_dump/print_r/var_export render a bigint array key as an integer
--FILE--
<?php
$a = [2 ** 100 => 'x'];

var_dump($a);
print_r($a);
var_export($a);
echo "\n";

// The display digit limit applies when a bigint key is rendered. Storage never
// throws, only rendering does (mirrors a displayed bigint scalar).
ini_set('zend.int_string_max_digits', 640);
$b = [10 ** 1000 => 'y'];
var_dump(isset($b[10 ** 1000]));
try {
    var_dump($b);
} catch (ValueError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECT--
array(1) {
  [1267650600228229401496703205376]=>
  string(1) "x"
}
Array
(
    [1267650600228229401496703205376] => x
)
array (
  1267650600228229401496703205376 => 'x',
)
bool(true)
array(1) {
}
ValueError: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

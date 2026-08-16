--TEST--
bigint: printers render big array keys as integers
--FILE--
<?php
$a = [2 ** 100 => 'x', '099' => 'y', -(2 ** 100) => 'z', 5 => 'w', '42' => 'x', 'plain' => 'v'];

var_dump($a);
print_r($a);
echo "\n";
var_export($a);
echo "\n";
debug_zval_dump([2 ** 100 => 'x', '099' => 'y', '42' => 'z']);

$source = var_export($a, true);
$round = eval('return ' . $source . ';');
var_dump($round === $a);

$obj = (object) ['99999999999999999999' => 1, '099' => 2, '42' => 3];

var_dump($obj);
print_r($obj);
echo "\n";
var_export($obj);
echo "\n";
?>
--EXPECT--
array(6) {
  [1267650600228229401496703205376]=>
  string(1) "x"
  ["099"]=>
  string(1) "y"
  [-1267650600228229401496703205376]=>
  string(1) "z"
  [5]=>
  string(1) "w"
  [42]=>
  string(1) "x"
  ["plain"]=>
  string(1) "v"
}
Array
(
    [1267650600228229401496703205376] => x
    [099] => y
    [-1267650600228229401496703205376] => z
    [5] => w
    [42] => x
    [plain] => v
)

array (
  1267650600228229401496703205376 => 'x',
  '099' => 'y',
  -1267650600228229401496703205376 => 'z',
  5 => 'w',
  42 => 'x',
  'plain' => 'v',
)
array(3) refcount(1){
  [1267650600228229401496703205376]=>
  string(1) "x" interned
  ["099"]=>
  string(1) "y" interned
  [42]=>
  string(1) "z" interned
}
bool(true)
object(stdClass)#1 (3) {
  ["99999999999999999999"]=>
  int(1)
  ["099"]=>
  int(2)
  ["42"]=>
  int(3)
}
stdClass Object
(
    [99999999999999999999] => 1
    [099] => 2
    [42] => 3
)

(object) array(
   '99999999999999999999' => 1,
   '099' => 2,
   '42' => 3,
)

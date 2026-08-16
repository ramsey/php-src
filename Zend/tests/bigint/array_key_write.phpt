--TEST--
bigint: array keys accept the write path over all of Z
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$a = [];
$a[2 ** 100] = 'x';
check('isset positive big key', isset($a[2 ** 100]));
check('read positive big key', $a[2 ** 100]);

$a[-(2 ** 100)] = 'y';
check('isset negative big key', isset($a[-(2 ** 100)]));
check('read negative big key', $a[-(2 ** 100)]);

check('canonical decimal string reads same element', $a['1267650600228229401496703205376']);

$a['+1267650600228229401496703205376'] = 'plus';
$a['01267650600228229401496703205376'] = 'leadingzero';
check('count after non-canonical siblings', count($a));
check('plus-prefixed key stays distinct', $a['+1267650600228229401496703205376']);
check('leading-zero key stays distinct', $a['01267650600228229401496703205376']);
check('bigint key unaffected by non-canonical siblings', $a[2 ** 100]);

check('array_key_exists true', array_key_exists(2 ** 100, $a));
check('array_key_exists false', array_key_exists(2 ** 100 + 1, $a));

unset($a[2 ** 100]);
check('isset after unset', isset($a[2 ** 100]));
check('array_key_exists after unset', array_key_exists(2 ** 100, $a));

$b = [];
$b[2 ** 100] = 'first';
$b[] = 'second';
check('append after big key keeps next index', array_key_last($b));
check('count after append', count($b));

$c = [2 ** 100 => 'literal'];
check('array literal with big key', isset($c[2 ** 100]));
check('array literal with big key read', $c[2 ** 100]);

$d = [];
$d[2 ** 100][] = 1;
check('nested append under big key', $d[2 ** 100]);

$e = [];
$e[2 ** 100] = 'x';
$e[2 ** 100] .= 'y';
check('compound assign under big key', $e[2 ** 100]);
?>
--EXPECT--
isset positive big key: bool(true)
read positive big key: string(1) "x"
isset negative big key: bool(true)
read negative big key: string(1) "y"
canonical decimal string reads same element: string(1) "x"
count after non-canonical siblings: int(4)
plus-prefixed key stays distinct: string(4) "plus"
leading-zero key stays distinct: string(11) "leadingzero"
bigint key unaffected by non-canonical siblings: string(1) "x"
array_key_exists true: bool(true)
array_key_exists false: bool(false)
isset after unset: bool(false)
array_key_exists after unset: bool(false)
append after big key keeps next index: int(0)
count after append: int(2)
array literal with big key: bool(true)
array literal with big key read: string(7) "literal"
nested append under big key: array(1) {
  [0]=>
  int(1)
}
compound assign under big key: string(2) "xy"

--TEST--
bigint: array keys read back as integers
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

function firstKey(array $arr): mixed {
    foreach ($arr as $k => $v) {
        return $k;
    }

    return null;
}

function definedVars(): array {
    ${'456'} = 'in-range';
    ${'99999999999999999999'} = 'out-of-range';

    return get_defined_vars();
}

$obj = (object) ['99999999999999999999' => 1];

$a = [
    2 ** 100 => 'x',
    -(2 ** 100) => 'y',
    5 => 'z',
    '42' => 'v',
    'plain' => 'w',
];

foreach ($a as $k => $v) {
    check('foreach key', $k);
}

check('foreach key inside a typed function', firstKey($a));
check('array_key_first is an int', is_int(array_key_first($a)));
check('array_key_first', array_key_first($a));
check('array_key_last', array_key_last($a));
check('key at the start', key($a));

next($a);
check('key after next', key($a));

end($a);
check('key at the end', key($a));

$byRef = [
    2 ** 100 => 'first',
    '099' => 'second',
    '42' => 'third',
];

foreach ($byRef as $k => &$v) {
    check('foreach by reference key', $k);
}

unset($v);

check('object keeps the string property name', $obj);
check('object property read by its name', $obj->{'99999999999999999999'});
check('get_object_vars gives the boxed key', get_object_vars($obj));

check('get_defined_vars', definedVars());

$GLOBALS[2 ** 100] = 'globals-bigint-value';
$GLOBALS['42'] = 'globals-int-value';
$globalsKey1 = null;
$globalsKey2 = null;

foreach ($GLOBALS as $k => $v) {
    if ($v === 'globals-bigint-value') {
        $globalsKey1 = $k;
    }
    if ($v === 'globals-int-value') {
        $globalsKey2 = $k;
    }
}

check('GLOBALS view bigint key is an int', is_int($globalsKey1));
check('GLOBALS view bigint key', $globalsKey1);
check('GLOBALS view int key is an int', is_int($globalsKey2));
check('GLOBALS view int key', $globalsKey2);
?>
--EXPECT--
foreach key: int(1267650600228229401496703205376)
foreach key: int(-1267650600228229401496703205376)
foreach key: int(5)
foreach key: int(42)
foreach key: string(5) "plain"
foreach key inside a typed function: int(1267650600228229401496703205376)
array_key_first is an int: bool(true)
array_key_first: int(1267650600228229401496703205376)
array_key_last: string(5) "plain"
key at the start: int(1267650600228229401496703205376)
key after next: int(-1267650600228229401496703205376)
key at the end: string(5) "plain"
foreach by reference key: int(1267650600228229401496703205376)
foreach by reference key: string(3) "099"
foreach by reference key: int(42)
object keeps the string property name: object(stdClass)#1 (1) {
  ["99999999999999999999"]=>
  int(1)
}
object property read by its name: int(1)
get_object_vars gives the boxed key: array(1) {
  [99999999999999999999]=>
  int(1)
}
get_defined_vars: array(2) {
  ["456"]=>
  string(8) "in-range"
  [99999999999999999999]=>
  string(12) "out-of-range"
}
GLOBALS view bigint key is an int: bool(true)
GLOBALS view bigint key: int(1267650600228229401496703205376)
GLOBALS view int key is an int: bool(true)
GLOBALS view int key: int(42)

--TEST--
bigint: object handlers run before box dispatch
--EXTENSIONS--
zend_test
--FILE--
<?php
$big = 2 ** 100;

$ops = [
    'obj + big' => static fn ($o, $big) => $o + $big,
    'big + obj' => static fn ($o, $big) => $big + $o,
    'obj - big' => static fn ($o, $big) => $o - $big,
    'big - obj' => static fn ($o, $big) => $big - $o,
    'obj * big' => static fn ($o, $big) => $o * $big,
    'big * obj' => static fn ($o, $big) => $big * $o,
    'obj / big' => static fn ($o, $big) => $o / $big,
    'big / obj' => static fn ($o, $big) => $big / $o,
    'obj ** big' => static fn ($o, $big) => $o ** $big,
    'big ** obj' => static fn ($o, $big) => $big ** $o,
    'obj % big' => static fn ($o, $big) => $o % $big,
    'big % obj' => static fn ($o, $big) => $big % $o,
    'obj | big' => static fn ($o, $big) => $o | $big,
    'big | obj' => static fn ($o, $big) => $big | $o,
    'obj & big' => static fn ($o, $big) => $o & $big,
    'big & obj' => static fn ($o, $big) => $big & $o,
    'obj ^ big' => static fn ($o, $big) => $o ^ $big,
    'big ^ obj' => static fn ($o, $big) => $big ^ $o,
    'obj << big' => static fn ($o, $big) => $o << $big,
    'big << obj' => static fn ($o, $big) => $big << $o,
    'obj >> big' => static fn ($o, $big) => $o >> $big,
    'big >> obj' => static fn ($o, $big) => $big >> $o,
];

$obj = new _ZendTestBigintOperand();

foreach ($ops as $label => $op) {
    echo $label . ': ';
    var_dump($op($obj, $big));
    echo $obj->lastOpcode . ' ' . $obj->lastOp1Type . ' ' . $obj->lastOp2Type . "\n";
}

$obj->fail = true;

foreach ($ops as $label => $op) {
    echo $label . ': ';
    try {
        var_dump($op($obj, $big));
    } catch (TypeError $e) {
        echo $e::class . ': ' . $e->getMessage() . "\n";
    }
}

$obj->castable = true;
$obj->castValue = 7;

$exactOps = [
    'obj % big',
    'big % obj',
    'obj | big',
    'big | obj',
    'obj & big',
    'big & obj',
    'obj ^ big',
    'big ^ obj',
    'obj << big',
    'big << obj',
    'obj >> big',
    'big >> obj',
];

foreach ($exactOps as $label) {
    echo $label . ': ';
    try {
        var_dump($ops[$label]($obj, $big));
    } catch (ArithmeticError $e) {
        echo $e::class . ': ' . $e->getMessage() . "\n";
    }
}
?>
--EXPECT--
obj + big: string(6) "marker"
ZEND_ADD _ZendTestBigintOperand bigint
big + obj: string(6) "marker"
ZEND_ADD bigint _ZendTestBigintOperand
obj - big: string(6) "marker"
ZEND_SUB _ZendTestBigintOperand bigint
big - obj: string(6) "marker"
ZEND_SUB bigint _ZendTestBigintOperand
obj * big: string(6) "marker"
ZEND_MUL _ZendTestBigintOperand bigint
big * obj: string(6) "marker"
ZEND_MUL bigint _ZendTestBigintOperand
obj / big: string(6) "marker"
ZEND_DIV _ZendTestBigintOperand bigint
big / obj: string(6) "marker"
ZEND_DIV bigint _ZendTestBigintOperand
obj ** big: string(6) "marker"
ZEND_POW _ZendTestBigintOperand bigint
big ** obj: string(6) "marker"
ZEND_POW bigint _ZendTestBigintOperand
obj % big: string(6) "marker"
ZEND_MOD _ZendTestBigintOperand bigint
big % obj: string(6) "marker"
ZEND_MOD bigint _ZendTestBigintOperand
obj | big: string(6) "marker"
ZEND_BW_OR _ZendTestBigintOperand bigint
big | obj: string(6) "marker"
ZEND_BW_OR bigint _ZendTestBigintOperand
obj & big: string(6) "marker"
ZEND_BW_AND _ZendTestBigintOperand bigint
big & obj: string(6) "marker"
ZEND_BW_AND bigint _ZendTestBigintOperand
obj ^ big: string(6) "marker"
ZEND_BW_XOR _ZendTestBigintOperand bigint
big ^ obj: string(6) "marker"
ZEND_BW_XOR bigint _ZendTestBigintOperand
obj << big: string(6) "marker"
ZEND_SL _ZendTestBigintOperand bigint
big << obj: string(6) "marker"
ZEND_SL bigint _ZendTestBigintOperand
obj >> big: string(6) "marker"
ZEND_SR _ZendTestBigintOperand bigint
big >> obj: string(6) "marker"
ZEND_SR bigint _ZendTestBigintOperand
obj + big: TypeError: Unsupported operand types: _ZendTestBigintOperand + int
big + obj: TypeError: Unsupported operand types: int + _ZendTestBigintOperand
obj - big: TypeError: Unsupported operand types: _ZendTestBigintOperand - int
big - obj: TypeError: Unsupported operand types: int - _ZendTestBigintOperand
obj * big: TypeError: Unsupported operand types: _ZendTestBigintOperand * int
big * obj: TypeError: Unsupported operand types: int * _ZendTestBigintOperand
obj / big: TypeError: Unsupported operand types: _ZendTestBigintOperand / int
big / obj: TypeError: Unsupported operand types: int / _ZendTestBigintOperand
obj ** big: TypeError: Unsupported operand types: _ZendTestBigintOperand ** int
big ** obj: TypeError: Unsupported operand types: int ** _ZendTestBigintOperand
obj % big: TypeError: Unsupported operand types: _ZendTestBigintOperand % int
big % obj: TypeError: Unsupported operand types: int % _ZendTestBigintOperand
obj | big: TypeError: Unsupported operand types: _ZendTestBigintOperand | int
big | obj: TypeError: Unsupported operand types: int | _ZendTestBigintOperand
obj & big: TypeError: Unsupported operand types: _ZendTestBigintOperand & int
big & obj: TypeError: Unsupported operand types: int & _ZendTestBigintOperand
obj ^ big: TypeError: Unsupported operand types: _ZendTestBigintOperand ^ int
big ^ obj: TypeError: Unsupported operand types: int ^ _ZendTestBigintOperand
obj << big: TypeError: Unsupported operand types: _ZendTestBigintOperand << int
big << obj: TypeError: Unsupported operand types: int << _ZendTestBigintOperand
obj >> big: TypeError: Unsupported operand types: _ZendTestBigintOperand >> int
big >> obj: TypeError: Unsupported operand types: int >> _ZendTestBigintOperand
obj % big: int(7)
big % obj: int(2)
obj | big: int(1267650600228229401496703205383)
big | obj: int(1267650600228229401496703205383)
obj & big: int(0)
big & obj: int(0)
obj ^ big: int(1267650600228229401496703205383)
big ^ obj: int(1267650600228229401496703205383)
obj << big: ArithmeticError: The libtommath bigint backend cannot shift left by more than 2147483647 bits
big << obj: int(162259276829213363391578010288128)
obj >> big: int(0)
big >> obj: int(9903520314283042199192993792)

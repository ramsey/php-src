--TEST--
bigint: exact comparison against numeric strings
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$sBig = '100000000000000000000';
$sBigPlus1 = '100000000000000000001';
$sLeadZero = '099999999999999999999';
$sNines20 = '99999999999999999999';
$sIntMaxPlus1 = '9223372036854775808';
$sFloatShaped = '9.007199254740992e15';

check('$sBig == $sBigPlus1', $sBig == $sBigPlus1);
check('$sBig < $sBigPlus1', $sBig < $sBigPlus1);
check('$sLeadZero == $sNines20', $sLeadZero == $sNines20);
check('PHP_INT_MAX == $sIntMaxPlus1', PHP_INT_MAX == $sIntMaxPlus1);
check('$sBig == 1e20', $sBig == 1e20);
check('$sNines20 == 1e20', $sNines20 == 1e20);
check('$sNines20 < 1e20', $sNines20 < 1e20);
check('9007199254740993 == $sFloatShaped', 9007199254740993 == $sFloatShaped);
check('9007199254740993 > $sFloatShaped', 9007199254740993 > $sFloatShaped);

// A digit span with a fractional tail is not a pure integer. The fraction
// makes it a distinct value.
$big = 99999999999999999999;
$sNines20Frac5 = '99999999999999999999.5';
$sNines20Frac6 = '99999999999999999999.6';
$sNines20FracZero = '99999999999999999999.000';
$sNines20ExpNeg30 = '99999999999999999999e-30';
$sNines20Exp2 = '99999999999999999999e2';
$dPow70 = 2.0 ** 70;
$sPow70Frac5 = '1180591620717411303424.5';
$fSafeIntPlus1 = 9.007199254740992e15;
$sSafeIntPlus1 = '9007199254740993';

check('$big == $sNines20Frac5', $big == $sNines20Frac5);
check('$big <=> $sNines20Frac5', $big <=> $sNines20Frac5);
check('$sNines20Frac5 <=> $big', $sNines20Frac5 <=> $big);

// The string's nearest double is exactly 2.0 ** 70; comparison against a
// float-shaped string always goes by nearest-double value.
check('$dPow70 == $sPow70Frac5', $dPow70 == $sPow70Frac5);
check('$sPow70Frac5 == $dPow70', $sPow70Frac5 == $dPow70);

// Same nearest-double rule applies through the bigint comparator.
$iPow70 = 2 ** 70;
check('$iPow70 == $sPow70Frac5', $iPow70 == $sPow70Frac5);
check('$iPow70 <=> $sPow70Frac5', $iPow70 <=> $sPow70Frac5);
check('$iPow70 == $dPow70', $iPow70 == $dPow70);

// Both operands are numeric strings; the fractional tail still breaks the
// pure-integer collision.
check('$sNines20 == $sNines20Frac5', $sNines20 == $sNines20Frac5);
check('$sNines20 <=> $sNines20Frac5', $sNines20 <=> $sNines20Frac5);
check('$sNines20Frac5 <=> $sNines20', $sNines20Frac5 <=> $sNines20);

// A negative exponent shrinks the value far below the digit span's own
// magnitude.
check('5 <=> $sNines20ExpNeg30', 5 <=> $sNines20ExpNeg30);
check('$sNines20ExpNeg30 <=> 5', $sNines20ExpNeg30 <=> 5);

// Trailing zeros past the point still spell a float. The string's double
// value is what gets compared.
check('$big == $sNines20FracZero', $big == $sNines20FracZero);

// Two float-shaped strings collapse to the same double at this magnitude.
check('$sNines20Frac5 == $sNines20Frac6', $sNines20Frac5 == $sNines20Frac6);

// A positive exponent grows the value far past the digit span's magnitude.
check('$big <=> $sNines20Exp2', $big <=> $sNines20Exp2);

// A double within the safe-integer band compared exactly against an
// adjacent long-shaped string.
check('$fSafeIntPlus1 == $sSafeIntPlus1', $fSafeIntPlus1 == $sSafeIntPlus1);

$pairs = [
    '$sBig <=> $sBigPlus1' => [$sBig, $sBigPlus1],
    '$sLeadZero <=> $sNines20' => [$sLeadZero, $sNines20],
    '$big <=> $sNines20Frac5' => [$big, $sNines20Frac5],
    '$sNines20 <=> $sNines20Frac5' => [$sNines20, $sNines20Frac5],
    '5 <=> $sNines20ExpNeg30' => [5, $sNines20ExpNeg30],
    '$dPow70 <=> $sPow70Frac5' => [$dPow70, $sPow70Frac5],
];

foreach ($pairs as $label => [$a, $b]) {
    check('symmetry: ' . $label, ($a <=> $b) === -($b <=> $a));
}
?>
--EXPECT--
$sBig == $sBigPlus1: bool(false)
$sBig < $sBigPlus1: bool(true)
$sLeadZero == $sNines20: bool(true)
PHP_INT_MAX == $sIntMaxPlus1: bool(false)
$sBig == 1e20: bool(true)
$sNines20 == 1e20: bool(false)
$sNines20 < 1e20: bool(true)
9007199254740993 == $sFloatShaped: bool(false)
9007199254740993 > $sFloatShaped: bool(true)
$big == $sNines20Frac5: bool(false)
$big <=> $sNines20Frac5: int(-1)
$sNines20Frac5 <=> $big: int(1)
$dPow70 == $sPow70Frac5: bool(true)
$sPow70Frac5 == $dPow70: bool(true)
$iPow70 == $sPow70Frac5: bool(true)
$iPow70 <=> $sPow70Frac5: int(0)
$iPow70 == $dPow70: bool(true)
$sNines20 == $sNines20Frac5: bool(false)
$sNines20 <=> $sNines20Frac5: int(-1)
$sNines20Frac5 <=> $sNines20: int(1)
5 <=> $sNines20ExpNeg30: int(1)
$sNines20ExpNeg30 <=> 5: int(-1)
$big == $sNines20FracZero: bool(false)
$sNines20Frac5 == $sNines20Frac6: bool(true)
$big <=> $sNines20Exp2: int(-1)
$fSafeIntPlus1 == $sSafeIntPlus1: bool(false)
symmetry: $sBig <=> $sBigPlus1: bool(true)
symmetry: $sLeadZero <=> $sNines20: bool(true)
symmetry: $big <=> $sNines20Frac5: bool(true)
symmetry: $sNines20 <=> $sNines20Frac5: bool(true)
symmetry: 5 <=> $sNines20ExpNeg30: bool(true)
symmetry: $dPow70 <=> $sPow70Frac5: bool(true)

--TEST--
bigint: boxed integers are always truthy: if, (bool), negation, ternary
--FILE--
<?php
$pos = 340282366920938463463374607431768211456;
$neg = -340282366920938463463374607431768211456;

if ($pos) {
    echo "pos is truthy\n";
}
if ($neg) {
    echo "neg is truthy\n";
}
var_dump((bool) $pos);
var_dump((bool) $neg);
var_dump(!$pos);
var_dump(!$neg);
echo ($pos ? 'y' : 'n') . "\n";
echo ($neg ? 'y' : 'n') . "\n";
?>
--EXPECT--
pos is truthy
neg is truthy
bool(true)
bool(true)
bool(false)
bool(false)
y
y

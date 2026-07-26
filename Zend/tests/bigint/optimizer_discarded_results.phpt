--TEST--
bigint: discarded division, modulo, and shift results are freed
--EXTENSIONS--
opcache
--INI--
opcache.enable_cli=1
opcache.optimization_level=-1
opcache.file_update_protection=0
--FILE--
<?php
function big(): int {
    $n = 340282366920938463463374607431768211456;

    return $n;
}

function long_max_plus_one(): int {
    $n = PHP_INT_MAX;
    $n++;

    return $n;
}

function discard(int $operand): void {
    $a = big();
    $b = big();
    $a / $operand;
    $a % ($b + 1);
    $a >> $operand;
}

function divide_long_min(int $divisor): void {
    $n = PHP_INT_MIN;
    $n / $divisor;
}

function quotient_of_long_min(int $divisor): int {
    $n = PHP_INT_MIN;

    return $n / $divisor;
}

for ($i = 0; $i < 5; $i++) {
    discard(1);
    divide_long_min(-1);
}

$a = big();
$b = big();
echo $a / 1 . "\n";
echo $a % ($b + 1) . "\n";
echo $a >> 1 . "\n";
var_dump(quotient_of_long_min(-1) === long_max_plus_one());
?>
--EXPECT--
340282366920938463463374607431768211456
340282366920938463463374607431768211456
170141183460469231731687303715884105728
bool(true)

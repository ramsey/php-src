--TEST--
bigint: boxed ints satisfy int type declarations (strict mode)
--EXTENSIONS--
zend_test
--FILE--
<?php
declare(strict_types=1);

function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$big = zend_test_bigint_make('123456789012345678901234567890');

function takes_int(int $x): bool {
    return zend_test_int_is_boxed($x);
}
check('strict int param keeps box boxed', takes_int($big));

function takes_int_or_float(int|float $x): bool {
    return zend_test_int_is_boxed($x);
}
check('strict int|float matches int arm first', takes_int_or_float($big));

function returns_int(mixed $x): int {
    return $x;
}
check('strict int return keeps box boxed', zend_test_int_is_boxed(returns_int($big)));

class Holder {
    public int $prop = 0;
    public static int $sprop = 0;
}

$h = new Holder();
$h->prop = $big;
check('strict typed prop keeps box boxed', zend_test_int_is_boxed($h->prop));

Holder::$sprop = $big;
check('strict typed static prop keeps box boxed', zend_test_int_is_boxed(Holder::$sprop));

$ref = &$h->prop;
$ref = zend_test_bigint_make('987654321098765432109876543210');
check('strict typed prop by ref keeps box boxed', zend_test_int_is_boxed($h->prop));
?>
--EXPECT--
strict int param keeps box boxed: bool(true)
strict int|float matches int arm first: bool(true)
strict int return keeps box boxed: bool(true)
strict typed prop keeps box boxed: bool(true)
strict typed static prop keeps box boxed: bool(true)
strict typed prop by ref keeps box boxed: bool(true)

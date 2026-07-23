--TEST--
bigint: boxed ints satisfy int type declarations (weak mode)
--EXTENSIONS--
zend_test
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$big = zend_test_bigint_make('123456789012345678901234567890');

function takes_int(int $x): bool {
    return zend_test_int_is_boxed($x);
}
check('int param keeps box boxed', takes_int($big));

function returns_int(mixed $x): int {
    return $x;
}
check('int return keeps box boxed', zend_test_int_is_boxed(returns_int($big)));

class Holder {
    public int $prop = 0;
    public static int $sprop = 0;
}

$h = new Holder();
$h->prop = $big;
check('typed prop keeps box boxed', zend_test_int_is_boxed($h->prop));

Holder::$sprop = $big;
check('typed static prop keeps box boxed', zend_test_int_is_boxed(Holder::$sprop));

$ref = &$h->prop;
$ref = zend_test_bigint_make('987654321098765432109876543210');
check('typed prop by ref keeps box boxed', zend_test_int_is_boxed($h->prop));

settype($ref, 'integer');
check('settype int through typed ref keeps box boxed', zend_test_int_is_boxed($h->prop));
?>
--EXPECT--
int param keeps box boxed: bool(true)
int return keeps box boxed: bool(true)
typed prop keeps box boxed: bool(true)
typed static prop keeps box boxed: bool(true)
typed prop by ref keeps box boxed: bool(true)
settype int through typed ref keeps box boxed: bool(true)

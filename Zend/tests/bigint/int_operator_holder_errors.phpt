--TEST--
bigint: integer operator holders release on every exit
--EXTENSIONS--
zend_test
--FILE--
<?php
try {
    $r = [] % '99999999999999999999';
    var_dump($r);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    $r = '99999999999999999999' % [];
    var_dump($r);
} catch (TypeError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

$r = '99999999999999999999abc' | 1;
var_dump($r);
var_dump(zend_test_int_is_boxed($r));

set_error_handler(static function (int $errno, string $errstr): bool {
    throw new ErrorException($errstr, $errno);
});

try {
    $r = '99999999999999999999abc' | 1;
    var_dump($r);
} catch (ErrorException $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
TypeError: Unsupported operand types: array % string
TypeError: Unsupported operand types: string % array

Warning: A non-numeric value encountered in %s on line %d
int(99999999999999999999)
bool(true)
ErrorException: A non-numeric value encountered

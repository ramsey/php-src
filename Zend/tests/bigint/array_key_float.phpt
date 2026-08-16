--TEST--
bigint: float array keys convert exactly
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

$a = [];
$a[1e20] = 'x';
check('huge integral float key read', $a[1e20]);

$a[1e20 + 0.5] = 'z';
check('huge float plus fraction stays integral, same key', $a[1e20]);

$a[2.5] = 'y';
check('fractional float key demotes to int(2)', $a[2]);

$a[-1e25] = 'w';
check('negative huge float key read', $a[-1e25]);

$a[3000000000.5] = 'v';
check('fractional float key beyond 32-bit long range demotes to int(3000000000)', $a[3000000000]);

set_error_handler(function (int $errno, string $errstr): bool {
    throw new Exception($errstr);
}, E_DEPRECATED);

$b = [];
try {
    $b[3.5] = 'blocked';
} catch (Exception $e) {
    check('deprecation promoted to exception', $e::class . ': ' . $e->getMessage());
}
check('no write happened on failure', count($b));

restore_error_handler();

$c = [];
$c[NAN] = 'nan';
check('NAN key demotes to int(0)', $c[0]);

$d = [];
$d[INF] = 'inf';
check('INF key demotes to int(0)', $d[0]);
?>
--EXPECTF--
huge integral float key read: string(1) "x"
huge float plus fraction stays integral, same key: string(1) "z"

Deprecated: Implicit conversion from float 2.5 to int loses precision in %s on line %d
fractional float key demotes to int(2): string(1) "y"
negative huge float key read: string(1) "w"

Deprecated: Implicit conversion from float 3000000000.5 to int loses precision in %s on line %d
fractional float key beyond 32-bit long range demotes to int(3000000000): string(1) "v"
deprecation promoted to exception: string(68) "Exception: Implicit conversion from float 3.5 to int loses precision"
no write happened on failure: int(0)

Warning: The float NAN is not representable as an int, cast occurred in %s on line %d

Deprecated: Implicit conversion from float NAN to int loses precision in %s on line %d
NAN key demotes to int(0): string(3) "nan"

Warning: The float INF is not representable as an int, cast occurred in %s on line %d
INF key demotes to int(0): string(3) "inf"

--TEST--
Bigint: ceil(), floor(), round() accept a bigint and return a float (strict mode)
--FILE--
<?php
declare(strict_types=1);

$pos = 2 ** 100;
$neg = -(2 ** 100);

foreach (['ceil', 'floor', 'round'] as $fn) {
    $r = $fn($pos);
    echo $fn . '(pos): ' . gettype($r) . ' ' . var_export(is_finite($r) && $r > 0, true) . "\n";
    $r = $fn($neg);
    echo $fn . '(neg): ' . gettype($r) . ' ' . var_export(is_finite($r) && $r < 0, true) . "\n";
}

// round() with an explicit precision and mode still returns a float for a bigint.
$r = round($pos, -3);
echo 'round(pos, -3): ' . gettype($r) . "\n";
$r = round($pos, 2, RoundingMode::HalfEven);
echo 'round(pos, 2, HalfEven): ' . gettype($r) . "\n";
?>
--EXPECT--
ceil(pos): double true
ceil(neg): double true
floor(pos): double true
floor(neg): double true
round(pos): double true
round(neg): double true
round(pos, -3): double
round(pos, 2, HalfEven): double

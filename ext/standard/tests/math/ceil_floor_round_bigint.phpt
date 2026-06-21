--TEST--
Bigint: ceil(), floor(), round() accept a bigint and return a float
--EXTENSIONS--
zend_test
--FILE--
<?php
$pos = 2 ** 100;
$neg = -(2 ** 100);

foreach (['ceil', 'floor', 'round'] as $fn) {
    $r = $fn($pos);
    echo $fn . '(pos): ' . gettype($r) . ' ' . var_export(is_finite($r) && $r > 0, true) . "\n";
    $r = $fn($neg);
    echo $fn . '(neg): ' . gettype($r) . ' ' . var_export(is_finite($r) && $r < 0, true) . "\n";
}

// A non-canonical in-range bigint behaves exactly like the equivalent long.
$small = zend_test_make_bigint('5');
var_dump(ceil($small));
var_dump(floor($small));
var_dump(round($small));
?>
--EXPECT--
ceil(pos): double true
ceil(neg): double true
floor(pos): double true
floor(neg): double true
round(pos): double true
round(neg): double true
float(5)
float(5)
float(5)

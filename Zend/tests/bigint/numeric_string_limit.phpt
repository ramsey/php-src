--TEST--
Bigint: zend.int_string_max_digits gates string-to-int conversion, not arithmetic on existing values or comparison
--EXTENSIONS--
zend_test
--FILE--
<?php
ini_set('zend.int_string_max_digits', 640);
$over = str_repeat('9', 700);   // over the 640-digit limit
$under = str_repeat('9', 600);  // under the limit

function check(string $label, callable $op): void {
    try {
        $op();
        echo "$label: no error\n";
    } catch (ValueError $e) {
        echo "$label: ValueError\n";
    }
}

// Under the limit, the conversion succeeds (a bigint).
var_dump(is_int($under + 0));

// Over the limit, every string-to-int conversion throws the same catchable ValueError.
check('add', fn() => $over + 0);
check('mul', fn() => $over * 2);
check('pow', fn() => $over ** 1);
check('cast', fn() => (int) $over);
check('intval', fn() => intval($over));
check('inc', function () use ($over) { $s = $over; $s++; });
check('param', fn() => (fn(int $x) => $x)($over));
check('flf', fn() => zend_test_flf_int($over));
check('settype', function () use ($over) {
    $s = $over;
    settype($s, 'int');
});

// Arithmetic on a value that is already a bigint never trips the limit.
$big = 10 ** 1000;
var_dump($big + 1 > $big);

// Comparison of huge numeric strings does not throw.
var_dump($over == $over);
var_dump($over == str_repeat('9', 700));

// Multiplication of huge numeric strings to produce a bigint that overflows the
// zend.int_string_max_digits limit does not throw and the result is a bigint.
var_dump(is_int($under * $under));

// Disabling the limit lets the conversion succeed.
ini_set('zend.int_string_max_digits', 0);
var_dump(is_int($over + 0));
?>
--EXPECT--
bool(true)
add: ValueError
mul: ValueError
pow: ValueError
cast: ValueError
intval: ValueError
inc: ValueError
param: ValueError
flf: ValueError
settype: ValueError
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

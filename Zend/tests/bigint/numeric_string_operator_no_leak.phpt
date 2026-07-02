--TEST--
Bigint: integer operators on promoted numeric strings do not leak temporaries
--INI--
opcache.enable_cli=0
--FILE--
<?php
ini_set('zend.int_string_max_digits', 640);
$s = (string) (PHP_INT_MAX + 1); // promotable out-of-range integer string
$over = str_repeat('9', 700);    // over the digit limit

// A debug build reports any leaked bigint temporary at shutdown, failing the test.
for ($i = 0; $i < 1000; $i++) {
    // single-operand promotion
    $r = $s % 7;
    $r = 7 % $s;
    $r = $s & 1;
    $r = $s | 0;
    $r = $s ^ 0;
    $r = $s << 1;
    $r = $s >> 1;

    // both operands promote
    $r = $s % $s;
    $r = $s >> $s;
    try {
        $r = $s << $s;
    } catch (MemoryError) {
        // ignore
    }

    // compound-assignment alias (result == op1)
    $a = $s; $a %= 2;
    $b = $s; $b &= 1;
    $c = $s; $c <<= 1;

    // op1 promotes, then op2 trips the digit limit (must release op1's bigint)
    try {
        $r = $s % $over;
    }  catch (ValueError) {
        // ignore
    }
    try {
        $r = $s << $over;
    } catch (ValueError) {
        // ignore
    }

    // division by zero after the dividend promotes
    try {
        $r = $s % 0;
    } catch (DivisionByZeroError) {
        // ignore
    }
}
echo "ok\n";
?>
--EXPECT--
ok

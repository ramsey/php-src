--TEST--
bigint: tracing jit exit snapshot restores spilled values from memory
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=64M
--FILE--
<?php
function probe(int $stop, int $n): mixed {
    $s0 = 1;
    $s1 = 2;
    $s2 = 3;
    $s3 = 4;
    $s4 = 5;
    $s5 = 6;
    $s6 = 7;
    $s7 = 8;
    $s8 = 9;
    $s9 = 10;
    $s10 = 11;
    $s11 = 12;
    $s12 = 13;
    $s13 = 14;
    $s14 = 15;
    $s15 = 16;
    $s16 = 17;
    $s17 = 18;
    $s18 = 19;
    $s19 = 20;
    $s20 = 21;
    $s21 = 22;
    $s22 = 23;
    $s23 = 24;
    $s24 = 25;
    $b = 0;
    while (++$b <= $n) {
        $s0 = ($s0 + $s1) & 0xFFFF;
        $s1 = ($s1 + $s2) & 0xFFFF;
        $s2 = ($s2 + $s3) & 0xFFFF;
        $s3 = ($s3 + $s4) & 0xFFFF;
        $s4 = ($s4 + $s5) & 0xFFFF;
        $s5 = ($s5 + $s6) & 0xFFFF;
        $s6 = ($s6 + $s7) & 0xFFFF;
        $s7 = ($s7 + $s8) & 0xFFFF;
        $s8 = ($s8 + $s9) & 0xFFFF;
        $s9 = ($s9 + $s10) & 0xFFFF;
        $s10 = ($s10 + $s11) & 0xFFFF;
        $s11 = ($s11 + $s12) & 0xFFFF;
        $s12 = ($s12 + $s13) & 0xFFFF;
        $s13 = ($s13 + $s14) & 0xFFFF;
        $s14 = ($s14 + $s15) & 0xFFFF;
        $s15 = ($s15 + $s16) & 0xFFFF;
        $s16 = ($s16 + $s17) & 0xFFFF;
        $s17 = ($s17 + $s18) & 0xFFFF;
        $s18 = ($s18 + $s19) & 0xFFFF;
        $s19 = ($s19 + $s20) & 0xFFFF;
        $s20 = ($s20 + $s21) & 0xFFFF;
        $s21 = ($s21 + $s22) & 0xFFFF;
        $s22 = ($s22 + $s23) & 0xFFFF;
        $s23 = ($s23 + $s24) & 0xFFFF;
        $s24 = ($s24 + $s0) & 0xFFFF;
        if ($b === $stop) {
            return PHP_INT_MAX + $s0 + $s1 + $s2 + $s3 + $s4 + $s5 + $s6 + $s7 + $s8 + $s9 + $s10 + $s11 + $s12 + $s13 + $s14 + $s15 + $s16 + $s17 + $s18 + $s19 + $s20 + $s21 + $s22 + $s23 + $s24;
        }
    }
    return 0;
}

probe(-1, 500);
$r = probe(400, 500);
echo 'int: ' . (is_int($r) ? 'ok' : 'FAIL') . "\n";
echo 'exact: ' . ($r - PHP_INT_MAX === 652567 ? 'ok' : 'FAIL ' . ($r - PHP_INT_MAX)) . "\n";
?>
--EXPECT--
int: ok
exact: ok

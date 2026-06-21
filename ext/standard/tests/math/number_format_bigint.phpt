--TEST--
Bigint: number_format() formats a bigint exactly as a grouped decimal string
--FILE--
<?php
$a = 10 ** 30;
$b = 2 ** 63;

echo number_format($a) . "\n";
echo number_format($a, 2) . "\n";
echo number_format(-$a) . "\n";
echo number_format($b, -1) . "\n";
echo number_format($b, -5) . "\n";
echo number_format(10 ** 30 - 1) . "\n";
echo number_format(10 ** 30 - 1, -1) . "\n";
echo number_format(-$b, -20) . "\n";
echo number_format($a, 4, ',', '.') . "\n";
?>
--EXPECT--
1,000,000,000,000,000,000,000,000,000,000
1,000,000,000,000,000,000,000,000,000,000.00
-1,000,000,000,000,000,000,000,000,000,000
9,223,372,036,854,775,810
9,223,372,036,854,800,000
999,999,999,999,999,999,999,999,999,999
1,000,000,000,000,000,000,000,000,000,000
0
1.000.000.000.000.000.000.000.000.000.000,0000

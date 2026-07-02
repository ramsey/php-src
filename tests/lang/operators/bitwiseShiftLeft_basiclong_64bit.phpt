--TEST--
Test << operator : 64bit long tests
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only");
?>
--INI--
memory_limit=128M
--FILE--
<?php

define("MAX_64Bit", 9223372036854775807);
define("MAX_32Bit", 2147483647);
define("MIN_64Bit", -9223372036854775807 - 1);
define("MIN_32Bit", -2147483647 - 1);

$longVals = array(
    MAX_64Bit, MIN_64Bit, MAX_32Bit, MIN_32Bit, MAX_64Bit - MAX_32Bit, MIN_64Bit - MIN_32Bit,
    MAX_32Bit + 1, MIN_32Bit - 1, MAX_32Bit * 2, (MAX_32Bit * 2) + 1, (MAX_32Bit * 2) - 1,
    MAX_64Bit -1, MAX_64Bit + 1, MIN_64Bit + 1, MIN_64Bit - 1
);

$otherVals = array(0, 1, -1, 7, 9, 65, -44, MAX_32Bit, MAX_64Bit);

error_reporting(E_ERROR);

foreach ($longVals as $longVal) {
   foreach($otherVals as $otherVal) {
      echo "--- testing: $longVal << $otherVal ---\n";
      try {
        var_dump($longVal<<$otherVal);
      } catch (ArithmeticError | MemoryError $e) {
        echo "Exception: " . $e->getMessage() . "\n";
      }
   }
}

foreach ($otherVals as $otherVal) {
   foreach($longVals as $longVal) {
      echo "--- testing: $otherVal << $longVal ---\n";
      try {
        var_dump($otherVal<<$longVal);
      } catch (ArithmeticError | MemoryError $e) {
        echo "Exception: " . $e->getMessage() . "\n";
      }
   }
}

?>
--EXPECT--
--- testing: 9223372036854775807 << 0 ---
int(9223372036854775807)
--- testing: 9223372036854775807 << 1 ---
int(-2)
--- testing: 9223372036854775807 << -1 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 7 ---
int(-128)
--- testing: 9223372036854775807 << 9 ---
int(-512)
--- testing: 9223372036854775807 << 65 ---
int(340282366920938463426481119284349108224)
--- testing: 9223372036854775807 << -44 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372036854775808 << 0 ---
int(-9223372036854775808)
--- testing: -9223372036854775808 << 1 ---
int(0)
--- testing: -9223372036854775808 << -1 ---
Exception: Bit shift by negative number
--- testing: -9223372036854775808 << 7 ---
int(0)
--- testing: -9223372036854775808 << 9 ---
int(0)
--- testing: -9223372036854775808 << 65 ---
int(-340282366920938463463374607431768211456)
--- testing: -9223372036854775808 << -44 ---
Exception: Bit shift by negative number
--- testing: -9223372036854775808 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372036854775808 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << 0 ---
int(2147483647)
--- testing: 2147483647 << 1 ---
int(4294967294)
--- testing: 2147483647 << -1 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 7 ---
int(274877906816)
--- testing: 2147483647 << 9 ---
int(1099511627264)
--- testing: 2147483647 << 65 ---
int(79228162477370849446124847104)
--- testing: 2147483647 << -44 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -2147483648 << 0 ---
int(-2147483648)
--- testing: -2147483648 << 1 ---
int(-4294967296)
--- testing: -2147483648 << -1 ---
Exception: Bit shift by negative number
--- testing: -2147483648 << 7 ---
int(-274877906944)
--- testing: -2147483648 << 9 ---
int(-1099511627776)
--- testing: -2147483648 << 65 ---
int(-79228162514264337593543950336)
--- testing: -2147483648 << -44 ---
Exception: Bit shift by negative number
--- testing: -2147483648 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -2147483648 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372034707292160 << 0 ---
int(9223372034707292160)
--- testing: 9223372034707292160 << 1 ---
int(-4294967296)
--- testing: 9223372034707292160 << -1 ---
Exception: Bit shift by negative number
--- testing: 9223372034707292160 << 7 ---
int(-274877906944)
--- testing: 9223372034707292160 << 9 ---
int(-1099511627776)
--- testing: 9223372034707292160 << 65 ---
int(340282366841710300949110269838224261120)
--- testing: 9223372034707292160 << -44 ---
Exception: Bit shift by negative number
--- testing: 9223372034707292160 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372034707292160 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372034707292160 << 0 ---
int(-9223372034707292160)
--- testing: -9223372034707292160 << 1 ---
int(4294967296)
--- testing: -9223372034707292160 << -1 ---
Exception: Bit shift by negative number
--- testing: -9223372034707292160 << 7 ---
int(274877906944)
--- testing: -9223372034707292160 << 9 ---
int(1099511627776)
--- testing: -9223372034707292160 << 65 ---
int(-340282366841710300949110269838224261120)
--- testing: -9223372034707292160 << -44 ---
Exception: Bit shift by negative number
--- testing: -9223372034707292160 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372034707292160 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483648 << 0 ---
int(2147483648)
--- testing: 2147483648 << 1 ---
int(4294967296)
--- testing: 2147483648 << -1 ---
Exception: Bit shift by negative number
--- testing: 2147483648 << 7 ---
int(274877906944)
--- testing: 2147483648 << 9 ---
int(1099511627776)
--- testing: 2147483648 << 65 ---
int(79228162514264337593543950336)
--- testing: 2147483648 << -44 ---
Exception: Bit shift by negative number
--- testing: 2147483648 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483648 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -2147483649 << 0 ---
int(-2147483649)
--- testing: -2147483649 << 1 ---
int(-4294967298)
--- testing: -2147483649 << -1 ---
Exception: Bit shift by negative number
--- testing: -2147483649 << 7 ---
int(-274877907072)
--- testing: -2147483649 << 9 ---
int(-1099511628288)
--- testing: -2147483649 << 65 ---
int(-79228162551157825740963053568)
--- testing: -2147483649 << -44 ---
Exception: Bit shift by negative number
--- testing: -2147483649 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -2147483649 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 4294967294 << 0 ---
int(4294967294)
--- testing: 4294967294 << 1 ---
int(8589934588)
--- testing: 4294967294 << -1 ---
Exception: Bit shift by negative number
--- testing: 4294967294 << 7 ---
int(549755813632)
--- testing: 4294967294 << 9 ---
int(2199023254528)
--- testing: 4294967294 << 65 ---
int(158456324954741698892249694208)
--- testing: 4294967294 << -44 ---
Exception: Bit shift by negative number
--- testing: 4294967294 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 4294967294 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 4294967295 << 0 ---
int(4294967295)
--- testing: 4294967295 << 1 ---
int(8589934590)
--- testing: 4294967295 << -1 ---
Exception: Bit shift by negative number
--- testing: 4294967295 << 7 ---
int(549755813760)
--- testing: 4294967295 << 9 ---
int(2199023255040)
--- testing: 4294967295 << 65 ---
int(158456324991635187039668797440)
--- testing: 4294967295 << -44 ---
Exception: Bit shift by negative number
--- testing: 4294967295 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 4294967295 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 4294967293 << 0 ---
int(4294967293)
--- testing: 4294967293 << 1 ---
int(8589934586)
--- testing: 4294967293 << -1 ---
Exception: Bit shift by negative number
--- testing: 4294967293 << 7 ---
int(549755813504)
--- testing: 4294967293 << 9 ---
int(2199023254016)
--- testing: 4294967293 << 65 ---
int(158456324917848210744830590976)
--- testing: 4294967293 << -44 ---
Exception: Bit shift by negative number
--- testing: 4294967293 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 4294967293 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775806 << 0 ---
int(9223372036854775806)
--- testing: 9223372036854775806 << 1 ---
int(-4)
--- testing: 9223372036854775806 << -1 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775806 << 7 ---
int(-256)
--- testing: 9223372036854775806 << 9 ---
int(-1024)
--- testing: 9223372036854775806 << 65 ---
int(340282366920938463389587631136930004992)
--- testing: 9223372036854775806 << -44 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775806 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775806 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775808 << 0 ---
int(9223372036854775808)
--- testing: 9223372036854775808 << 1 ---
int(18446744073709551616)
--- testing: 9223372036854775808 << -1 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775808 << 7 ---
int(1180591620717411303424)
--- testing: 9223372036854775808 << 9 ---
int(4722366482869645213696)
--- testing: 9223372036854775808 << 65 ---
int(340282366920938463463374607431768211456)
--- testing: 9223372036854775808 << -44 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775808 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775808 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372036854775807 << 0 ---
int(-9223372036854775807)
--- testing: -9223372036854775807 << 1 ---
int(2)
--- testing: -9223372036854775807 << -1 ---
Exception: Bit shift by negative number
--- testing: -9223372036854775807 << 7 ---
int(128)
--- testing: -9223372036854775807 << 9 ---
int(512)
--- testing: -9223372036854775807 << 65 ---
int(-340282366920938463426481119284349108224)
--- testing: -9223372036854775807 << -44 ---
Exception: Bit shift by negative number
--- testing: -9223372036854775807 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372036854775807 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372036854775809 << 0 ---
int(-9223372036854775809)
--- testing: -9223372036854775809 << 1 ---
int(-18446744073709551618)
--- testing: -9223372036854775809 << -1 ---
Exception: Bit shift by negative number
--- testing: -9223372036854775809 << 7 ---
int(-1180591620717411303552)
--- testing: -9223372036854775809 << 9 ---
int(-4722366482869645214208)
--- testing: -9223372036854775809 << 65 ---
int(-340282366920938463500268095579187314688)
--- testing: -9223372036854775809 << -44 ---
Exception: Bit shift by negative number
--- testing: -9223372036854775809 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -9223372036854775809 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 0 << 9223372036854775807 ---
int(0)
--- testing: 0 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 0 << 2147483647 ---
int(0)
--- testing: 0 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 0 << 9223372034707292160 ---
int(0)
--- testing: 0 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 0 << 2147483648 ---
int(0)
--- testing: 0 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 0 << 4294967294 ---
int(0)
--- testing: 0 << 4294967295 ---
int(0)
--- testing: 0 << 4294967293 ---
int(0)
--- testing: 0 << 9223372036854775806 ---
int(0)
--- testing: 0 << 9223372036854775808 ---
int(0)
--- testing: 0 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 0 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: 1 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 1 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 1 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 1 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 1 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 1 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 1 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: -1 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: -1 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: -1 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: -1 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: -1 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -1 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: -1 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: 7 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 7 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 7 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 7 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 7 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 7 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 7 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: 9 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 9 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 9 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 9 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 9 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 9 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: 65 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 65 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 65 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 65 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 65 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 65 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 65 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: -44 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: -44 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: -44 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: -44 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: -44 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: -44 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: -44 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 2147483647 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 2147483647 << -9223372036854775809 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 9223372036854775807 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << -9223372036854775808 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 2147483647 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << -2147483648 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 9223372034707292160 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << -9223372034707292160 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 2147483648 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << -2147483649 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << 4294967294 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << 4294967295 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << 4294967293 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << 9223372036854775806 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << 9223372036854775808 ---
Exception: Bit shift produces an integer too large to fit in the configured memory limit
--- testing: 9223372036854775807 << -9223372036854775807 ---
Exception: Bit shift by negative number
--- testing: 9223372036854775807 << -9223372036854775809 ---
Exception: Bit shift by negative number

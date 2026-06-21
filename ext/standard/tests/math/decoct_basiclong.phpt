--TEST--
Test decoct - bigint values for 64bit and 32bit platforms
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


foreach ($longVals as $longVal) {
   echo "--- testing: $longVal ---\n";
   try {
      var_dump(decoct($longVal));
   } catch (TypeError $exception) {
       echo $exception->getMessage() . "\n";
   }
}

?>
--EXPECT--
--- testing: 9223372036854775807 ---
string(21) "777777777777777777777"
--- testing: -9223372036854775808 ---
string(23) "-1000000000000000000000"
--- testing: 2147483647 ---
string(11) "17777777777"
--- testing: -2147483648 ---
string(12) "-20000000000"
--- testing: 9223372034707292160 ---
string(21) "777777777760000000000"
--- testing: -9223372034707292160 ---
string(22) "-777777777760000000000"
--- testing: 2147483648 ---
string(11) "20000000000"
--- testing: -2147483649 ---
string(12) "-20000000001"
--- testing: 4294967294 ---
string(11) "37777777776"
--- testing: 4294967295 ---
string(11) "37777777777"
--- testing: 4294967293 ---
string(11) "37777777775"
--- testing: 9223372036854775806 ---
string(21) "777777777777777777776"
--- testing: 9223372036854775808 ---
string(22) "1000000000000000000000"
--- testing: -9223372036854775807 ---
string(22) "-777777777777777777777"
--- testing: -9223372036854775809 ---
string(23) "-1000000000000000000001"

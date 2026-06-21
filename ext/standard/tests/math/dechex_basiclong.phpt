--TEST--
Test dechex - bigint values for 64bit and 32bit platforms
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
        var_dump(dechex($longVal));
    } catch (TypeError $exception) {
        echo $exception->getMessage() . "\n";
    }
}

?>
--EXPECT--
--- testing: 9223372036854775807 ---
string(16) "7fffffffffffffff"
--- testing: -9223372036854775808 ---
string(17) "-8000000000000000"
--- testing: 2147483647 ---
string(8) "7fffffff"
--- testing: -2147483648 ---
string(9) "-80000000"
--- testing: 9223372034707292160 ---
string(16) "7fffffff80000000"
--- testing: -9223372034707292160 ---
string(17) "-7fffffff80000000"
--- testing: 2147483648 ---
string(8) "80000000"
--- testing: -2147483649 ---
string(9) "-80000001"
--- testing: 4294967294 ---
string(8) "fffffffe"
--- testing: 4294967295 ---
string(8) "ffffffff"
--- testing: 4294967293 ---
string(8) "fffffffd"
--- testing: 9223372036854775806 ---
string(16) "7ffffffffffffffe"
--- testing: 9223372036854775808 ---
string(16) "8000000000000000"
--- testing: -9223372036854775807 ---
string(17) "-7fffffffffffffff"
--- testing: -9223372036854775809 ---
string(17) "-8000000000000001"

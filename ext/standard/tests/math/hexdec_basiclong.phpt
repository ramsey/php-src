--TEST--
Test hexdec - bigint values for 64bit and 32bit platforms
--FILE--
<?php

define("MAX_64Bit", 9223372036854775807);
define("MAX_32Bit", 2147483647);
define("MIN_64Bit", -9223372036854775807 - 1);
define("MIN_32Bit", -2147483647 - 1);

$hexLongStrs = array(
   '7'.str_repeat('f',15),
   str_repeat('f',16),
   '7'.str_repeat('f',7),
   str_repeat('f',8),
   '7'.str_repeat('f',16),
   str_repeat('f',18),
   '7'.str_repeat('f',8),
   str_repeat('f',9)
);


foreach ($hexLongStrs as $strVal) {
   echo "--- testing: $strVal ---\n";
   var_dump(hexdec($strVal));
}

?>
--EXPECT--
--- testing: 7fffffffffffffff ---
int(9223372036854775807)
--- testing: ffffffffffffffff ---
int(18446744073709551615)
--- testing: 7fffffff ---
int(2147483647)
--- testing: ffffffff ---
int(4294967295)
--- testing: 7ffffffffffffffff ---
int(147573952589676412927)
--- testing: ffffffffffffffffff ---
int(4722366482869645213695)
--- testing: 7ffffffff ---
int(34359738367)
--- testing: fffffffff ---
int(68719476735)

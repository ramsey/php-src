--TEST--
http_build_query() renders a bigint value as its decimal digits
--INI--
opcache.enable_cli=0
--FILE--
<?php

echo http_build_query(['x' => 2 ** 70]) . "\n";
echo http_build_query(['a' => -(2 ** 70), 'b' => 5]) . "\n";

// Over the digit limit, the conversion throws a catchable ValueError.
ini_set('zend.int_string_max_digits', 1000);

try {
    http_build_query(['big' => 10 ** 2000]);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
x=1180591620717411303424
a=-1180591620717411303424&b=5
Integer too large to convert to string; it exceeds the limit of 1000 digits, configurable via the zend.int_string_max_digits setting

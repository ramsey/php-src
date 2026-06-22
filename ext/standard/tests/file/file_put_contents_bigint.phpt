--TEST--
file_put_contents() writes a bigint as its decimal digits
--INI--
opcache.enable_cli=0
--FILE--
<?php

$file = __DIR__ . '/file_put_contents_bigint.tmp';

file_put_contents($file, 2 ** 70);
var_dump(file_get_contents($file));

file_put_contents($file, -(2 ** 70));
var_dump(file_get_contents($file));

// Over the digit limit, the conversion throws a catchable ValueError.
ini_set('zend.int_string_max_digits', 1000);

try {
    file_put_contents($file, 10 ** 2000);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/file_put_contents_bigint.tmp');
?>
--EXPECT--
string(22) "1180591620717411303424"
string(23) "-1180591620717411303424"
Integer too large to convert to string; it exceeds the limit of 1000 digits, configurable via the zend.int_string_max_digits setting

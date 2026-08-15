--TEST--
PRE_INC/DEC numeric string
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.file_update_protection=0
opcache.protect_memory=1
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip: 64-bit only"); ?>
--FILE--
<?php
function test($b) {
    $a = "0";
    $i = 0;
    $n = 0;
    while (is_numeric($a) && $n < 23) {
        $a .= $b;
        $a--;
        $i .= $a;
        $i++;
        $n++;
    }
    var_dump($a, $i);
}
test("0");
?>
--EXPECTF--
Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d

Deprecated: Increment on non-numeric string is deprecated, use str_increment() instead in %s on line %d
int(-11111111111111111111111)
string(300) "0-2-12-112-1112-11112-111112-1111112-11111112-111111112-1111111112-11111111112-111111111112-1111111111112-11111111111112-111111111111112-1111111111111112-11111111111111112-111111111111111112-1111111111111111112-11111111111111111112-111111111111111111112-1111111111111111111112-11111111111111111111112"

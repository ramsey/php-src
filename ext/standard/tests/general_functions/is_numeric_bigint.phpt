--TEST--
is_numeric() treats a bigint as numeric
--FILE--
<?php
var_dump(is_numeric(2 ** 100));
var_dump(is_numeric(-(2 ** 100)));
?>
--EXPECT--
bool(true)
bool(true)

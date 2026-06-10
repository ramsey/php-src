--TEST--
abs() with bigint operands under strict_types
--FILE--
<?php

declare(strict_types=1);

var_dump(abs(2**100) === 2**100);
var_dump(abs(-(2**100)) === 2**100);
var_dump(abs(-9223372036854775808));
var_dump(abs(-2147483648));

?>
--EXPECT--
bool(true)
bool(true)
int(9223372036854775808)
int(2147483648)

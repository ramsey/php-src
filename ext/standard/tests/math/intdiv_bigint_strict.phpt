--TEST--
intdiv() with bigint operands in strict mode
--FILE--
<?php
declare(strict_types=1);

// Bigint operands pass through strict mode
var_dump(intdiv(2 ** 100, 3));

?>
--EXPECT--
int(422550200076076467165567735125)

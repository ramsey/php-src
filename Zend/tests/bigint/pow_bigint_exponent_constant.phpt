--TEST--
Bigint: a constant pow with a bigint exponent is deferred, not folded into an uncatchable error
--INI--
memory_limit=128M
opcache.enable_cli=0
--FILE--
<?php

try {
    var_dump(2 ** (2 ** 70));
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

try {
    var_dump(2 ** '1180591620717411303424');
} catch (MemoryError $e) {
    echo $e->getMessage() . "\n";
}

var_dump(1 ** (2 ** 70));
var_dump((-1) ** (2 ** 70 + 1));

?>
--EXPECT--
Exponentiation produces an integer too large to fit in the configured memory limit
Exponentiation produces an integer too large to fit in the configured memory limit
int(1)
int(-1)

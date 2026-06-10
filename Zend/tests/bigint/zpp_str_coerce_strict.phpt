--TEST--
Bigint: strict mode string targets still reject big integers (TypeError)
--INI--
opcache.enable_cli=0
--FILE--
<?php
declare(strict_types=1);

function g(string $s): string {
    return $s;
}

try {
    g(2 ** 100);
} catch (TypeError $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}

?>
--EXPECTF--
TypeError: g(): Argument #1 ($s) must be of type string, int given%s

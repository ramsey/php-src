--TEST--
Test compression level range with bigint arguments
--EXTENSIONS--
zlib
--FILE--
<?php
try {
    gzcompress('x', 9223372036854775808);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    gzcompress('x', 1e100);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    gzcompress('x', 10);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

try {
    zlib_encode('x', ZLIB_ENCODING_RAW, 10 ** 30);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

var_dump(gzuncompress(gzcompress('x', 9)));

try {
    gzcompress('x', -1, 9223372036854775808);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}
?>
--EXPECTF--
ValueError: gzcompress(): Argument #2 ($level) must be between -1 and 9
ValueError: gzcompress(): Argument #2 ($level) must be between -1 and 9
ValueError: gzcompress(): Argument #2 ($level) must be between -1 and 9
ValueError: zlib_encode(): Argument #3 ($level) must be between -1 and 9
string(1) "x"
ValueError: gzcompress(): Argument #3 ($encoding) must be between %i and %i

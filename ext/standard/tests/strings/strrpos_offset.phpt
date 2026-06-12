--TEST--
strrpos() offset integer overflow
--FILE--
<?php

try {
    var_dump(strrpos("t", "t", PHP_INT_MAX+1));
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}

try {
    strrpos(1024, 1024, -PHP_INT_MAX);
} catch (ValueError $exception) {
    echo $exception->getMessage() . "\n";
}

try {
    strrpos(1024, "te", -PHP_INT_MAX);
} catch (ValueError $exception) {
    echo $exception->getMessage() . "\n";
}

try {
    strrpos(1024, 1024, -PHP_INT_MAX-1);
} catch (ValueError $exception) {
    echo $exception->getMessage() . "\n";
}

try {
    strrpos(1024, "te", -PHP_INT_MAX-1);
} catch (ValueError $exception) {
    echo $exception->getMessage() . "\n";
}

echo "Done\n";
?>
--EXPECTF--
strrpos(): Argument #3 ($offset) must be between -%d and %d
strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
strrpos(): Argument #3 ($offset) must be contained in argument #1 ($haystack)
Done

--TEST--
Bigint: a bigint call argument appears in an exception stack trace
--FILE--
<?php

function f($a)
{
    throw new Exception('boom');
}

try {
    f(2 ** 70);
} catch (Exception $e) {
    $trace = $e->getTraceAsString();
    var_dump(str_contains($trace, '1180591620717411303424'));
}

?>
--EXPECT--
bool(true)

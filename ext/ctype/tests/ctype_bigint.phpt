--TEST--
ctype: a bigint argument behaves like a large integer (its decimal string)
--EXTENSIONS--
ctype
--FILE--
<?php
$big = 2 ** 100;
$neg = -(2 ** 100);

// A bigint is out of the single-byte range, so each ctype function treats it the
// same way it treats a large long of the same sign (the deprecated "int as string"
// behavior). The @ suppresses the deprecation notice each call emits.
$fns = ['ctype_digit', 'ctype_alnum', 'ctype_alpha', 'ctype_print', 'ctype_graph', 'ctype_punct'];
foreach ($fns as $fn) {
    var_dump(@$fn($big) === @$fn(999999));
    var_dump(@$fn($neg) === @$fn(-999999));
}

// The deprecation still fires for a bigint argument.
ctype_digit($big);
?>
--EXPECTF--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

Deprecated: ctype_digit(): Argument of type int will be interpreted as string in the future in %s on line %d

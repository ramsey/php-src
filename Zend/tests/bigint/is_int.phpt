--TEST--
bigint: is_int/is_scalar accept a boxed int via the compiled opcode and the real builtin
--FILE--
<?php
function check(string $expr, mixed $result): void {
    echo $expr . ': ';
    var_dump($result);
}

$big = 123456789012345678901234567890;

check('is_int($big)', is_int($big));
check('is_scalar($big)', is_scalar($big));

// The literal calls above compile to the ZEND_TYPE_CHECK opcode; calling
// through first-class callables reaches the real builtin functions instead.
$is_int = is_int(...);
$is_scalar = is_scalar(...);

check('$is_int($big)', $is_int($big));
check('$is_scalar($big)', $is_scalar($big));

check('gettype($big)', gettype($big));
?>
--EXPECT--
is_int($big): bool(true)
is_scalar($big): bool(true)
$is_int($big): bool(true)
$is_scalar($big): bool(true)
gettype($big): string(7) "integer"

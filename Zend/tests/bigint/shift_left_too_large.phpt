--TEST--
Bigint: with no memory limit, a << count beyond the backend's reach throws a catchable ArithmeticError
--EXTENSIONS--
zend_test
--SKIPIF--
<?php if (zend_test_bigint_backend() !== 'libtommath') die('skip libtommath backend only'); ?>
--INI--
memory_limit=-1
opcache.enable_cli=0
--FILE--
<?php
$tooBig = 2147483648;   // 2^31, one past the C int max (2147483647) that bounds
                        // libtommath's shift count; a valid PHP 64-bit int but out of the backend's reach

// Constant operands must stay a catchable runtime error, not an uncatchable
// compile-time fatal.
try {
    var_dump(1 << 2147483648);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

try {
    var_dump(1 << (2 ** 70));
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Runtime long operand.
try {
    var_dump(1 << $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Bigint operand, out-of-reach long count (32-bit platform).
$big = 2147483647 + 1;
try {
    var_dump($big << $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Bigint operand, out-of-reach long count (64-bit platform).
$big = 9223372036854775807 + 1;
try {
    var_dump($big << $tooBig);
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// Bigint count (always out of reach when positive).
try {
    var_dump(1 << ($big + 1));
} catch (ArithmeticError $e) {
    echo $e->getMessage(), "\n";
}

// On the thrown path the compound-assignment target keeps its old value.
$x = 5;
try {
    $x <<= $tooBig;
} catch (ArithmeticError $e) {
}
var_dump($x);
?>
--EXPECT--
The libtommath bigint backend cannot shift left by more than 2147483647 bits
The libtommath bigint backend cannot shift left by more than 2147483647 bits
The libtommath bigint backend cannot shift left by more than 2147483647 bits
The libtommath bigint backend cannot shift left by more than 2147483647 bits
The libtommath bigint backend cannot shift left by more than 2147483647 bits
The libtommath bigint backend cannot shift left by more than 2147483647 bits
int(5)

--TEST--
Bigint: strict-mode int return types accept and keep big integers
--EXTENSIONS--
zend_test
--INI--
opcache.enable_cli=0
--FILE--
<?php declare(strict_types=1);

// Strict mode: plain int return via expression
function strict_int_return_expr(): int {
    return 2 ** 100;
}
var_dump(strict_int_return_expr());

// Strict mode: plain int return via variable
function strict_int_return_var(): int {
    $x = 2 ** 100;
    return $x;
}
var_dump(strict_int_return_var());

// Strict mode: int | float return stays int
function strict_int_or_float_return(): int | float {
    return 2 ** 100;
}
$v = strict_int_or_float_return();
var_dump(is_int($v));
var_dump($v);

// Strict mode: nullable ?int return
function strict_nullable_int_return(): ?int {
    return 2 ** 100;
}
var_dump(strict_nullable_int_return());

function strict_nullable_int_return_null(): ?int {
    return null;
}
var_dump(strict_nullable_int_return_null());

// Strict mode: method return type
class Calc {
    public function compute(): int {
        return 2 ** 100;
    }
}
$c = new Calc();
var_dump($c->compute());

// In-range bigint via zend_test_make_bigint
function strict_in_range_bigint_return(): int {
    return zend_test_make_bigint('5');
}
var_dump(strict_in_range_bigint_return());

?>
--EXPECT--
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
bool(true)
int(1267650600228229401496703205376)
int(1267650600228229401496703205376)
NULL
int(1267650600228229401496703205376)
int(5)

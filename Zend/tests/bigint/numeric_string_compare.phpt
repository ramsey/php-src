--TEST--
Bigint: a bigint compares against a numeric string as an integer
--INI--
opcache.enable_cli=0
--FILE--
<?php
$b = PHP_INT_MAX + 1;  // a bigint
$bs = (string) $b;     // its decimal string

// bigint vs in-range string
var_dump($b > "5");
var_dump($b <=> (string) PHP_INT_MAX);

// bigint vs out-of-range integer string
var_dump($b == $bs);
var_dump($b <=> $bs);
var_dump($b == (string) ($b + 1));
var_dump($b < (string) ($b + 1));
var_dump($b > (string) ($b + 1));
var_dump(($b * 3) <=> (string) ($b * 3 - 1));

// negative bigint
$n = -$b - 1;
var_dump($n == (string) $n);
var_dump($n < (string) ($n + 1));

// bigint vs float string
var_dump($b > "1.5");
var_dump($b < "9.9e40");

// A non-numeric string makes a number compare byte-wise as its decimal string.
var_dump($b <=> "abc");

// A numeric string compares just like the bigint literal it represents.
var_dump(($b <=> (string) ($b + 1)) === ($b <=> ($b + 1)));
var_dump(($b <=> (string) ($b - 1)) === ($b <=> ($b - 1)));
?>
--EXPECT--
bool(true)
int(1)
bool(true)
int(0)
bool(false)
bool(true)
bool(false)
int(1)
bool(true)
bool(true)
bool(true)
bool(true)
int(-1)
bool(true)
bool(true)

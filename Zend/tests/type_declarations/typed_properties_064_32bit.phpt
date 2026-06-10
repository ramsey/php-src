--TEST--
Typed property on by-ref array value
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--FILE--
<?php

$a = new class {
    public int $foo = 1;
};

$_ = [&$a->foo];

$_[0] += 1;
var_dump($a->foo);

$_[0] .= "1";
var_dump($a->foo);

try {
    $_[0] .= "e50";
} catch (Error $e) { echo $e->getMessage(), "\n"; }
var_dump($a->foo);

$_[0]--;
var_dump($a->foo);

--$_[0];
var_dump($a->foo);

$a->foo = PHP_INT_MIN;

$_[0]--;
var_dump($a->foo);

--$_[0];
var_dump($a->foo);

$a->foo = PHP_INT_MAX;

$_[0]++;
var_dump($a->foo);

++$_[0];
var_dump($a->foo);

$_[0] = 0;
try {
    $_[0] = [];
} catch (Error $e) { echo $e->getMessage(), "\n"; }
var_dump($a->foo);

$_[0] = 1;
var_dump($a->foo);

?>
--EXPECT--
int(2)
int(21)
Cannot assign string to reference held by property class@anonymous::$foo of type int
int(21)
int(20)
int(19)
int(-2147483649)
int(-2147483650)
int(2147483648)
int(2147483649)
Cannot assign array to reference held by property class@anonymous::$foo of type int
int(0)
int(1)

--TEST--
Test increment functions on typed property references
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--FILE--
<?php
$foo = new class {
    public ?int $bar;
};

$bar = &$foo->bar;

$bar *= 1;

var_dump($bar--);
var_dump(--$bar);
var_dump(++$bar);
var_dump($bar++);

$bar = PHP_INT_MAX;

var_dump($bar++);
var_dump(++$bar);

$bar = PHP_INT_MIN;

var_dump($bar--);
var_dump(--$bar);

?>
--EXPECT--
int(0)
int(-2)
int(-1)
int(-1)
int(2147483647)
int(2147483649)
int(-2147483648)
int(-2147483650)

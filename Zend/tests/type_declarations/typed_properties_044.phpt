--TEST--
Test increment functions on typed property references
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64 bit platform only"); ?>
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
int(9223372036854775807)
int(9223372036854775809)
int(-9223372036854775808)
int(-9223372036854775810)

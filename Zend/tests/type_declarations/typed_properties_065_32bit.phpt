--TEST--
Typed property on by-ref array dimension
--SKIPIF--
<?php if (PHP_INT_SIZE > 4) die("skip this test is for 32-bit platform only"); ?>
--FILE--
<?php

$a = new class implements ArrayAccess {
    public int $foo = 1;

    function offsetExists($o): bool { return 1; }
    function &offsetGet($o): mixed { return $this->foo; }
    function offsetSet($o, $v): void { print "offsetSet($v)\n"; }
    function offsetUnset($o): void { print "offsetUnset() ?!?"; }
};

$a[0] += 1;
var_dump($a->foo);

$a[0] .= "1";
var_dump($a->foo);

$a[0] .= "e50";
var_dump($a->foo);

$a[0]--;
var_dump($a->foo);

--$a[0];
var_dump($a->foo);

$a->foo = PHP_INT_MIN;

$a[0]--;
var_dump($a->foo);

--$a[0];
var_dump($a->foo);

$a->foo = PHP_INT_MAX;

$a[0]++;
var_dump($a->foo);

++$a[0];
var_dump($a->foo);

?>
--EXPECT--
offsetSet(2)
int(1)
offsetSet(11)
int(1)
offsetSet(1e50)
int(1)
int(0)
int(-1)
int(-2147483649)
int(-2147483650)
int(2147483648)
int(2147483649)

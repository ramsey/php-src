--TEST--
bigint: interpolated offsets outside int range box at lex time
--FILE--
<?php
class Probe implements ArrayAccess
{
    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    public function offsetGet(mixed $offset): mixed
    {
        var_dump($offset);

        return '';
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }
}

$a = [
    2 ** 100 => 'big',
    -(2 ** 100) => 'neg',
    5 => 'five',
    '42' => 'forty-two',
    '9223372036854775808' => 'boundary',
    '0x10' => 'hex',
];
$s = 'abcdef';
$p = new Probe();

var_dump("$a[1267650600228229401496703205376]");
var_dump("$a[-1267650600228229401496703205376]");
var_dump("$s[1267650600228229401496703205376]");
var_dump($s[1267650600228229401496703205376]);
var_dump("$a[5]");
var_dump("$a[42]");
var_dump("$a[9223372036854775808]");
var_dump("$a[0x10]");

"$p[1267650600228229401496703205376]";
"$p[-1267650600228229401496703205376]";
"$p[5]";
"$p[0x10]";
?>
--EXPECTF--
string(3) "big"
string(3) "neg"

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
string(0) ""
string(4) "five"
string(9) "forty-two"
string(8) "boundary"
string(3) "hex"
int(1267650600228229401496703205376)
int(-1267650600228229401496703205376)
int(5)
string(4) "0x10"

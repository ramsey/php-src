--TEST--
bigint: (int) over a persisted big literal is exact on every call under opcache
--EXTENSIONS--
opcache
--INI--
opcache.enable_cli=1
opcache.optimization_level=-1
opcache.file_update_protection=0
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

function fromBox(): int {
    $x = 99999999999999999999;
    return (int) $x;
}

function fromFloat(): int {
    $x = 1e20;
    return (int) $x;
}

function fromString(): int {
    $x = '99999999999999999999';
    return (int) $x;
}

function folded(): int {
    return (int) 99999999999999999999;
}

check('first box', fromBox());
check('second box', fromBox());
check('first float', fromFloat());
check('second float', fromFloat());
check('first string', fromString());
check('second string', fromString());
check('first folded', folded());
check('second folded', folded());
check('folded identity', folded() === 10 ** 20 - 1);
?>
--EXPECT--
first box: int(99999999999999999999)
second box: int(99999999999999999999)
first float: int(100000000000000000000)
second float: int(100000000000000000000)
first string: int(99999999999999999999)
second string: int(99999999999999999999)
first folded: int(99999999999999999999)
second folded: int(99999999999999999999)
folded identity: bool(true)

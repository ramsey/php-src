--TEST--
bigint: a persisted big literal string offset reports its value on every call
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

function readBig(string $s): string {
    return $s[1267650600228229401496703205376];
}

function issetBig(string $s): bool {
    return isset($s[1267650600228229401496703205376]);
}

check('first read', readBig('abc'));
check('second read', readBig('abc'));
check('first isset', issetBig('abc'));
check('second isset', issetBig('abc'));
?>
--EXPECTF--
Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
first read: string(0) ""

Warning: Uninitialized string offset 1267650600228229401496703205376 in %s on line %d
second read: string(0) ""
first isset: bool(false)
second isset: bool(false)

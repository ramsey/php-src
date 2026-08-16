--TEST--
bigint: a persisted big literal array key survives repeat calls under opcache
--INI--
opcache.enable_cli=1
--FILE--
<?php
function check(string $label, mixed $value): void {
    echo $label . ': ';
    var_dump($value);
}

function useBigKey(): array {
    $a = [];
    $a[1267650600228229401496703205376] = 'x';
    return $a;
}

$first = useBigKey();
$second = useBigKey();

check('first call value at big key', $first[2 ** 100]);
check('second call value at big key', $second[2 ** 100]);
check('second call isset at big key', isset($second[2 ** 100]));
?>
--EXPECT--
first call value at big key: string(1) "x"
second call value at big key: string(1) "x"
second call isset at big key: bool(true)

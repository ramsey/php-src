--TEST--
Incrementing max int values 64bit
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only"); ?>
--INI--
precision=14
--FILE--
<?php

$values = [
    PHP_INT_MAX,
    (string)PHP_INT_MAX
];

foreach ($values as $var) {
    $var++;
    var_dump($var);
}
echo "Done\n";
?>
--EXPECT--
int(9223372036854775808)
int(9223372036854775808)
Done

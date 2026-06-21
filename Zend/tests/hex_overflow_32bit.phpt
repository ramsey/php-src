--TEST--
testing integer overflow (32bit)
--INI--
serialize_precision=14
--SKIPIF--
<?php if (PHP_INT_SIZE != 4) die("skip this test is for 32bit platform only"); ?>
--FILE--
<?php

$doubles = array(
    0x1736123FFFAAA,
    0XFFFFFFFFFFFFFFFFFF,
    0xAAAAAAAAAAAAAAEEEEEEEEEBBB,
    0x66666666666666666777777,
    );

foreach ($doubles as $d) {
    $l = $d;
    var_dump($l);
}

echo "Done\n";
?>
--EXPECT--
int(408336029711018)
int(4722366482869645213695)
int(13521606402434447024358161312699)
int(1980704062856608439839717239)
Done

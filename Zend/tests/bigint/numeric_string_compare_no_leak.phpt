--TEST--
Bigint: comparing a bigint against a numeric string does not leak temporaries
--INI--
opcache.enable_cli=0
--FILE--
<?php
$b = PHP_INT_MAX + 1; // a bigint
$bs = (string) $b;

// A debug build reports any leaked temporary at shutdown.
for ($i = 0; $i < 1000; $i++) {
    // compare_bigint_to_string branches
    $r = $b <=> "5";                       // in-range string
    $r = $b <=> $bs;                       // out-of-range integer string
    $r = $b == (string) ($b + 1);          // out-of-range integer string
    $r = $b <=> "1.5";                     // float string
    $r = $b <=> "abc";                     // non-numeric
    $r = "abc" <=> $b;                     // reversed arm

    // string-string magnitude tie
    $r = "10000000000000000000" <=> "9999999999999999999";
    $r = "09223372036854775808" == "9223372036854775808";

    // sort a mix of bigints and numeric strings
    $a = [$b, "5", $bs, (string) ($b + 1)];
    sort($a);
}
echo "ok\n";
?>
--EXPECT--
ok

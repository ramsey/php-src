--TEST--
Octal integer overflow
--EXTENSIONS--
filter
--FILE--
<?php
function octal_inc($s) {
    $len = strlen($s);
    while ($len > 0) {
        $len--;
        if ($s[$len] != '7') {
            $s[$len] = $s[$len] + 1;
            return $s;
        }
        $s[$len] = '0';
    }
    return '1'.$s;
}


$s = sprintf("%o", PHP_INT_MAX);
var_dump(is_long(filter_var('0'.$s, FILTER_VALIDATE_INT, array("flags"=>FILTER_FLAG_ALLOW_OCTAL))));

$s = octal_inc($s);
var_dump(is_long(filter_var('0'.$s, FILTER_VALIDATE_INT, array("flags"=>FILTER_FLAG_ALLOW_OCTAL))));

// 2 ** 64 - 1 is the all-ones 64-bit value.
$s = sprintf("%o", 2 ** 64 - 1);
var_dump(is_long(filter_var('0'.$s, FILTER_VALIDATE_INT, array("flags"=>FILTER_FLAG_ALLOW_OCTAL))));

$s = octal_inc($s);
var_dump(filter_var('0'.$s, FILTER_VALIDATE_INT, array("flags"=>FILTER_FLAG_ALLOW_OCTAL)));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
int(18446744073709551616)

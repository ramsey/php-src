--TEST--
Bigint: size/offset/length/duration parameters clamp a bigint argument
--INI--
opcache.enable_cli=0
--FILE--
<?php

$a = [1, 2, 3];
var_dump(array_slice($a, 2 ** 70));
var_dump(array_slice($a, 0, 2 ** 70) === $a);
var_dump(array_slice($a, -(2 ** 70)) === $a);

var_dump(chunk_split('abcdef', 2 ** 70, 'X'));
try {
    chunk_split('abc', -(2 ** 70), 'X');
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

$tmp = tempnam(sys_get_temp_dir(), 'bgint');
$fp = fopen($tmp, 'r+');
var_dump(is_bool(@ftruncate($fp, 2 ** 70)));
fclose($fp);
@unlink($tmp);


// Unable to test the positive cases, since these would sleep for all eternity.
//sleep(2 ** 70);
//usleep(2 ** 70);
//time_nanosleep(2 ** 70, 0);

try {
    sleep(-(2 ** 70));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
try {
    usleep(-(2 ** 70));
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}
try {
    time_nanosleep(-(2 ** 70), 0);
} catch (ValueError $e) {
    echo $e->getMessage() . "\n";
}

?>
--EXPECT--
array(0) {
}
bool(true)
bool(true)
string(7) "abcdefX"
chunk_split(): Argument #2 ($length) must be greater than 0
bool(true)
sleep(): Argument #1 ($seconds) must be greater than or equal to 0
usleep(): Argument #1 ($microseconds) must be greater than or equal to 0
time_nanosleep(): Argument #1 ($seconds) must be greater than or equal to 0

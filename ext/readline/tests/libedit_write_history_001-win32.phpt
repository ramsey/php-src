--TEST--
readline_write_history(): Basic test
--EXTENSIONS--
readline
--SKIPIF--
<?php if (!function_exists('readline_add_history')) die("skip");
if (READLINE_LIB != "libedit") die("skip libedit only");
if(substr(PHP_OS, 0, 3) != 'WIN' ) {
    die('skip windows only test');
}
?>
--FILE--
<?php

$name = tempnam(sys_get_temp_dir(), 'readline.tmp');

readline_add_history('foo');
readline_add_history('');
readline_add_history(1);
readline_write_history($name);

var_dump(file_get_contents($name));

unlink($name);

?>
--EXPECT--
string(6) "foo
1
"

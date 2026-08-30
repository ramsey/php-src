--TEST--
substr() clamps out-of-range offset and length
--FILE--
<?php
$substr = 'substr';
var_dump($substr('hello', 9223372036854775808));
var_dump($substr('hello', -(10 ** 30)));
var_dump($substr('hello', 1, 10 ** 30));
var_dump($substr('hello', 1, -(10 ** 30)));
var_dump($substr('hello', 1e100));
var_dump($substr('hello', 1, null));
var_dump(substr('hello', 9223372036854775808));
var_dump(substr('hello', -(10 ** 30)));
var_dump(substr('hello', 2));
var_dump(substr('hello', 1, 10 ** 30));
var_dump(substr('hello', 1, -(10 ** 30)));
var_dump(substr('hello', 1, 2));
eval('declare(strict_types=1); var_dump(substr(\'hello\', 10 ** 30));');
eval('declare(strict_types=1); var_dump(substr(\'hello\', 1, 10 ** 30));');
eval('declare(strict_types=1); $substr = \'substr\'; var_dump($substr(\'hello\', -(10 ** 30), 10 ** 30));');
?>
--EXPECT--
string(0) ""
string(5) "hello"
string(4) "ello"
string(0) ""
string(0) ""
string(4) "ello"
string(0) ""
string(5) "hello"
string(3) "llo"
string(4) "ello"
string(0) ""
string(2) "el"
string(0) ""
string(4) "ello"
string(5) "hello"

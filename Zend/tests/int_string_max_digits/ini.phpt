--TEST--
zend.int_string_max_digits: registration, default, runtime changes, and the 640 floor
--FILE--
<?php
var_dump(ini_get('zend.int_string_max_digits'));         // default
var_dump(ini_set('zend.int_string_max_digits', '1000')); // old value returned
var_dump(ini_get('zend.int_string_max_digits'));
var_dump(ini_set('zend.int_string_max_digits', '0'));    // 0 = unlimited, allowed
var_dump(ini_get('zend.int_string_max_digits'));
var_dump(ini_set('zend.int_string_max_digits', '100'));  // below the 640 floor -> rejected
var_dump(ini_get('zend.int_string_max_digits'));         // unchanged
var_dump(ini_set('zend.int_string_max_digits', '-5'));   // negative -> rejected
var_dump(ini_set('zend.int_string_max_digits', '640'));  // floor boundary -> allowed
var_dump(ini_get('zend.int_string_max_digits'));
?>
--EXPECT--
string(4) "4300"
string(4) "4300"
string(4) "1000"
string(4) "1000"
string(1) "0"
bool(false)
string(1) "0"
bool(false)
string(1) "0"
string(3) "640"

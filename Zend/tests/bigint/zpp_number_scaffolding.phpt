--TEST--
Bigint: unmigrated Z_PARAM_NUMBER consumers degrade bigint to float
--EXTENSIONS--
zend_test
--FILE--
<?php
// zend_number() is a zend_test shim that parses its argument with Z_PARAM_NUMBER.
// That layer has not been migrated to Z_PARAM_INT_OR_FLOAT, so a bigint argument
// degrades to a float via the scaffolding arm in zend_parse_arg_number_slow. This
// test pins that deliberate behavior so we'll know which callers remain when we
// sweep the codebase to migrate the rest.
$big = 2 ** 100;
$result = zend_number($big);
var_dump(gettype($result));

// Value is approximate; just check it's a finite positive number.
var_dump(is_finite($result) && $result > 0);
?>
--EXPECT--
string(6) "double"
bool(true)

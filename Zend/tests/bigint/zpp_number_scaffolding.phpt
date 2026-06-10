--TEST--
Bigint: unmigrated Z_PARAM_NUMBER consumers degrade bigint to float
--FILE--
<?php
// ceil() uses Z_PARAM_NUMBER and has not been migrated to Z_PARAM_INT_OR_FLOAT.
// It must still work with a bigint, degrading to float via the scaffolding arm in
// zend_parse_arg_number_slow. This test pins that deliberate behavior so we'll
// know which callers remain when we sweep the codebase to migrate the rest.
$big = 2 ** 100;
$result = ceil($big);
var_dump(gettype($result));

// Value is approximate; just check it's a finite positive number.
var_dump(is_finite($result) && $result > 0);
?>
--EXPECT--
string(6) "double"
bool(true)

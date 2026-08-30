--TEST--
intdiv functionality
--FILE--
<?php
var_dump(intdiv(3, 2));
var_dump(intdiv(-3, 2));
var_dump(intdiv(3, -2));
var_dump(intdiv(-3, -2));
var_dump(intdiv(PHP_INT_MAX, PHP_INT_MAX));
var_dump(intdiv(PHP_INT_MIN, PHP_INT_MIN));
var_dump(intdiv(PHP_INT_MIN, -1) === -1 * PHP_INT_MIN);
try {
  var_dump(intdiv(1, 0));
} catch (Throwable $e) {
  echo "Exception: " . $e->getMessage() . "\n";
}

?>
--EXPECT--
int(1)
int(-1)
int(-1)
int(1)
int(1)
int(1)
bool(true)
Exception: Division by zero

--TEST--
bigint: internal int parameters reject boxed integers with a range ValueError; string, float, and bool weak coercions accept them
--EXTENSIONS--
zend_test
--INI--
zend.exception_ignore_args=0
--FILE--
<?php
$big = zend_test_bigint_make('340282366920938463463374607431768211456');

try {
    str_pad('a', $big);
} catch (ValueError $e) {
    echo $e::class . ': ' . $e->getMessage() . "\n";
}

var_dump(strlen($big));
var_dump(sqrt($big) === sqrt(2.0 ** 128));
var_dump(array_slice([1, 2], 0, null, $big) === [1, 2]);

$rows = [[(string) $big => 'alpha'], ['other' => 'beta']];
var_dump(array_column($rows, $big));
?>
--EXPECTF--
ValueError: str_pad(): Argument #2 ($length) must be between %i and %i
int(39)
bool(true)
bool(true)
array(1) {
  [0]=>
  string(5) "alpha"
}

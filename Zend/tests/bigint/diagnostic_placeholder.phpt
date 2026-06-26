--TEST--
Bigint: an oversized integer shows a placeholder in diagnostic output, but var_export/serialize still throw
--INI--
zend.int_string_max_digits=640
opcache.enable_cli=0
--FILE--
<?php

$big = 1 << 3000;

// Diagnostic output should show the placeholder.
var_dump($big);
debug_zval_dump($big);
print_r($big);
echo "\n";

// The scalar formatter shared by stack-trace args and the unhandled-match error
// also shows the placeholder.
try {
    $r = match ($big) {
        0 => 'zero',
    };
} catch (UnhandledMatchError $e) {
    echo $e->getMessage() . "\n";
}

// Round-trippable forms must throw rather than emit a lossy or invalid value.
try {
    var_export($big);
} catch (ValueError $e) {
    echo 'var_export: ' . $e->getMessage() . "\n";
}

try {
    serialize($big);
} catch (ValueError $e) {
    echo 'serialize: ' . $e->getMessage() . "\n";
}

?>
--EXPECT--
int(<integer too large to display>)
int(<integer too large to display>)
<integer too large to display>
Unhandled match case <integer too large to display>
var_export: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
serialize: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting

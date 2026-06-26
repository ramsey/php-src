--TEST--
zend.int_string_max_digits: var_dump/debug_zval_dump/print_r show a placeholder; var_export/serialize throw
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$over  = 10 ** 640; // 641 digits
$under = 10 ** 639; // 640 digits

// Diagnostic functions show a placeholder, never the digits or a throw.
foreach (['var_dump', 'debug_zval_dump', 'print_r'] as $fn) {
    ob_start();
    $fn($over);
    $out = ob_get_clean();
    echo "$fn: " . (str_contains($out, '<integer too large to display>') ? 'placeholder' : 'UNEXPECTED') . "\n";
}

// Round-trippable forms throw rather than emit a lossy or invalid value.
foreach (['var_export', 'serialize'] as $fn) {
    try {
        $fn($over);
        echo "$fn: no throw\n";
    } catch (\ValueError $e) {
        echo "$fn: " . $e->getMessage() . "\n";
    }
}

// Under the limit, everything still renders the full value.
ob_start();
var_dump($under);
$out = ob_get_clean();

echo 'var_dump under ok: ' . (str_starts_with($out, 'int(1') ? 'yes' : 'no') . "\n";
echo 'var_export under ok: ' . (strlen(var_export($under, true)) === 640 ? 'yes' : 'no') . "\n";
?>
--EXPECT--
var_dump: placeholder
debug_zval_dump: placeholder
print_r: placeholder
var_export: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
serialize: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
var_dump under ok: yes
var_export under ok: yes

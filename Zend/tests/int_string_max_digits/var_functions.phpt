--TEST--
zend.int_string_max_digits: var_dump/debug_zval_dump/var_export throw over the limit
--INI--
zend.int_string_max_digits=640
--FILE--
<?php
$over  = 10 ** 640; // 641 digits
$under = 10 ** 639; // 640 digits

foreach (['var_dump', 'debug_zval_dump', 'var_export'] as $fn) {
    try {
        ob_start();
        $fn($over);
        ob_end_clean();
        echo "$fn: no throw\n";
    } catch (\ValueError $e) {
        while (ob_get_level()) { ob_end_clean(); }
        echo "$fn: ", $e->getMessage(), "\n";
    }
}

ob_start(); var_dump($under); $out = ob_get_clean();
echo "var_dump under ok: ", (str_starts_with($out, "int(1") ? "yes" : "no"), "\n";
echo "var_export under ok: ", (strlen(var_export($under, true)) === 640 ? "yes" : "no"), "\n";
?>
--EXPECT--
var_dump: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
debug_zval_dump: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
var_export: Integer too large to convert to string; it exceeds the limit of 640 digits, configurable via the zend.int_string_max_digits setting
var_dump under ok: yes
var_export under ok: yes

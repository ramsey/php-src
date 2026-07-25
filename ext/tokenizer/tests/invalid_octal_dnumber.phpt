--TEST--
Invalid octal number reports as T_LNUMBER
--EXTENSIONS--
tokenizer
--FILE--
<?php
echo token_name(token_get_all('<?php 0177777777777777777777787')[1][0]), "\n";
?>
--EXPECT--
T_LNUMBER

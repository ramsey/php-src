--TEST--
bigint: big integer literals survive opcache SHM persistence
--EXTENSIONS--
opcache
--INI--
opcache.enable_cli=1
opcache.optimization_level=-1
opcache.file_update_protection=0
--FILE--
<?php
require __DIR__ . '/literal_opcache_common.inc';
?>
--EXPECT--
340282366920938463463374607431768211456
9223372036854775808
340282366920938463463374607431768211456
340282366920938463463374607431768211456
bool(true)
bool(true)

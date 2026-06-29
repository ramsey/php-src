--TEST--
DateTime/DateTimeImmutable::createFromTimestamp() rejects an out-of-range bigint timestamp
--FILE--
<?php

foreach (['DateTime', 'DateTimeImmutable'] as $class) {
    foreach ([2 ** 100, -(2 ** 100)] as $ts) {
        try {
            $class::createFromTimestamp($ts);
        } catch (DateRangeError $e) {
            echo $e->getMessage() . "\n";
        }
    }
}

?>
--EXPECT--
DateTime::createFromTimestamp(): Argument #1 ($timestamp) must be a finite number between -9223372036854775808 and 9223372036854775807.999999, 1.26765e+30 given
DateTime::createFromTimestamp(): Argument #1 ($timestamp) must be a finite number between -9223372036854775808 and 9223372036854775807.999999, -1.26765e+30 given
DateTimeImmutable::createFromTimestamp(): Argument #1 ($timestamp) must be a finite number between -9223372036854775808 and 9223372036854775807.999999, 1.26765e+30 given
DateTimeImmutable::createFromTimestamp(): Argument #1 ($timestamp) must be a finite number between -9223372036854775808 and 9223372036854775807.999999, -1.26765e+30 given

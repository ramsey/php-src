--TEST--
Test dechex() function : usage variations - different data types as $num arg
--INI--
precision=14
opcache.enable_cli=0
--FILE--
<?php
echo "*** Testing dechex() : usage variations ***\n";

$inputs = [
       // int data
/*1*/  0,
       1,
       12345,
       -2345,
       18446744073709551615,
       18446744073709551616,
       4294967295,
       4294967296,

       // float data
/* 7*/ 12.3456789000e10,

       // boolean data
/* 8*/ true,
       false,
       TRUE,
       FALSE,

       // empty data
/*12*/ "",
       '',
];

// loop through each element of $inputs to check the behaviour of dechex()
foreach ($inputs as $i => $input) {
    $iterator = $i + 1;
    echo "\n-- Iteration $iterator --\n";
    try {
        var_dump(dechex($input));
    } catch (TypeError $exception) {
        echo $exception->getMessage() . "\n";
    }
}

?>
--EXPECT--
*** Testing dechex() : usage variations ***

-- Iteration 1 --
string(1) "0"

-- Iteration 2 --
string(1) "1"

-- Iteration 3 --
string(4) "3039"

-- Iteration 4 --
string(4) "-929"

-- Iteration 5 --
string(16) "ffffffffffffffff"

-- Iteration 6 --
string(17) "10000000000000000"

-- Iteration 7 --
string(8) "ffffffff"

-- Iteration 8 --
string(9) "100000000"

-- Iteration 9 --
string(10) "1cbe991a08"

-- Iteration 10 --
string(1) "1"

-- Iteration 11 --
string(1) "0"

-- Iteration 12 --
string(1) "1"

-- Iteration 13 --
string(1) "0"

-- Iteration 14 --
dechex(): Argument #1 ($num) must be of type int, string given

-- Iteration 15 --
dechex(): Argument #1 ($num) must be of type int, string given

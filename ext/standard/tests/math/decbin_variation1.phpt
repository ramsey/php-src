--TEST--
Test decbin() function : usage variations - different data types as $num arg
--INI--
precision=14
opcache.enable_cli=0
--FILE--
<?php
echo "*** Testing decbin() : usage variations ***\n";

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

// loop through each element of $inputs to check the behaviour of decbin()
foreach ($inputs as $i => $input) {
    $iterator = $i + 1;
    echo "\n-- Iteration $iterator --\n";
    try {
        var_dump(decbin($input));
    } catch (TypeError $exception) {
        echo $exception->getMessage() . "\n";
    }
}

?>
--EXPECT--
*** Testing decbin() : usage variations ***

-- Iteration 1 --
string(1) "0"

-- Iteration 2 --
string(1) "1"

-- Iteration 3 --
string(14) "11000000111001"

-- Iteration 4 --
string(13) "-100100101001"

-- Iteration 5 --
string(64) "1111111111111111111111111111111111111111111111111111111111111111"

-- Iteration 6 --
string(65) "10000000000000000000000000000000000000000000000000000000000000000"

-- Iteration 7 --
string(32) "11111111111111111111111111111111"

-- Iteration 8 --
string(33) "100000000000000000000000000000000"

-- Iteration 9 --
string(37) "1110010111110100110010001101000001000"

-- Iteration 10 --
string(1) "1"

-- Iteration 11 --
string(1) "0"

-- Iteration 12 --
string(1) "1"

-- Iteration 13 --
string(1) "0"

-- Iteration 14 --
decbin(): Argument #1 ($num) must be of type int, string given

-- Iteration 15 --
decbin(): Argument #1 ($num) must be of type int, string given

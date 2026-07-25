--TEST--
JIT MUL: 004 Overflow check for optmizing MUL to SHIFT
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.file_update_protection=0
;opcache.jit_debug=257
--EXTENSIONS--
opcache
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only");
?>
--FILE--
<?php

function mul2_8(int $a) {
  $res = $a * 8;  // shift cnt: 3
  var_dump($res);
}

function mul1_16(int $a) {
  $res = 16 * $a; // shift cnt: 4
  var_dump($res);
}

function mul2_big_int32(int $a) {
  $res = $a * 0x10000000; // shift cnt: 29
  var_dump($res);
}

function mul2_big_int64(int $a) {
  $res = $a * 0x100000000; // shift cnt: 32
  var_dump($res);
}

function mul2(int $a) {
  $res = $a * 2; // $a + $a
  var_dump($res);
}

mul2_8(3);
mul2_8(-11);
mul2_8(0x7fffffffffffffff);
mul1_16(3);
mul1_16(-13);
mul1_16(0x7fffffffffffffff);
mul2_big_int32(3);
mul2_big_int32(-3);
mul2_big_int32(0x10000000000);
mul2_big_int64(3);
mul2_big_int64(-3);
mul2_big_int64(0x100000000);
mul2(10);
mul2(0x7fffffffffffffff);
?>
--EXPECT--
int(24)
int(-88)
int(73786976294838206456)
int(48)
int(-208)
int(147573952589676412912)
int(805306368)
int(-805306368)
int(295147905179352825856)
int(12884901888)
int(-12884901888)
int(18446744073709551616)
int(20)
int(18446744073709551614)

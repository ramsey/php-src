--TEST--
bigint: compare fast paths stay exact beyond the float significand under tracing JIT
--INI--
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_buffer_size=16M
opcache.jit_hot_loop=1
--FILE--
<?php
function check(string $label, bool $ok): void {
    echo $label . ': ' . ($ok ? 'ok' : 'FAIL') . "\n";
}

$p = 2 ** 63 - 1;
$q = (float) $p;
for ($i = 0; $i < 200; $i++) {
    $eq = $p == $q;
    $ne = $p != $q;
    $lt = $p < $q;
    $le = $p <= $q;
    $ge = $p >= $q;
    $sp = $p <=> $q;
    $req = $q == $p;
    $rlt = $q < $p;
    $rsp = $q <=> $p;
}
check('2 ** 63 - 1 == (float) (2 ** 63 - 1) (int on left)', $eq === false);
check('2 ** 63 - 1 != (float) (2 ** 63 - 1) (int on left)', $ne === true);
check('2 ** 63 - 1 < (float) (2 ** 63 - 1) (int on left)', $lt === true);
check('2 ** 63 - 1 <= (float) (2 ** 63 - 1) (int on left)', $le === true);
check('2 ** 63 - 1 >= (float) (2 ** 63 - 1) (int on left)', $ge === false);
check('2 ** 63 - 1 <=> (float) (2 ** 63 - 1) (int on left)', $sp === -1);
check('(float) (2 ** 63 - 1) == 2 ** 63 - 1 (float on left)', $req === false);
check('(float) (2 ** 63 - 1) < 2 ** 63 - 1 (float on left)', $rlt === false);
check('(float) (2 ** 63 - 1) <=> 2 ** 63 - 1 (float on left)', $rsp === 1);

$p = 2 ** 53 + 1;
$q = (float) $p;
for ($i = 0; $i < 200; $i++) {
    $eq = $p == $q;
    $ne = $p != $q;
    $lt = $p < $q;
    $le = $p <= $q;
    $ge = $p >= $q;
    $sp = $p <=> $q;
    $req = $q == $p;
    $rlt = $q < $p;
    $rsp = $q <=> $p;
}
check('2 ** 53 + 1 == (float) (2 ** 53 + 1) (int on left)', $eq === false);
check('2 ** 53 + 1 != (float) (2 ** 53 + 1) (int on left)', $ne === true);
check('2 ** 53 + 1 < (float) (2 ** 53 + 1) (int on left)', $lt === false);
check('2 ** 53 + 1 <= (float) (2 ** 53 + 1) (int on left)', $le === false);
check('2 ** 53 + 1 >= (float) (2 ** 53 + 1) (int on left)', $ge === true);
check('2 ** 53 + 1 <=> (float) (2 ** 53 + 1) (int on left)', $sp === 1);
check('(float) (2 ** 53 + 1) == 2 ** 53 + 1 (float on left)', $req === false);
check('(float) (2 ** 53 + 1) < 2 ** 53 + 1 (float on left)', $rlt === true);
check('(float) (2 ** 53 + 1) <=> 2 ** 53 + 1 (float on left)', $rsp === -1);

$val = 2 ** 53 + 1;
$case_float = (float) (2 ** 53 + 1);
for ($i = 0; $i < 200; $i++) {
    switch ($val) {
        case $case_float:
            $switch_result = 'float-arm';
            break;
        default:
            $switch_result = 'default';
    }
}
check('switch stays exact under tracing', $switch_result === 'default');

$dsubject = (float) (2 ** 53 + 1);
for ($i = 0; $i < 200; $i++) {
    switch ($dsubject) {
        case 2 ** 53 + 1:
            $switch2_result = 'int-arm';
            break;
        default:
            $switch2_result = 'default';
    }
}
check('float-subject switch stays exact under tracing', $switch2_result === 'default');

$big = PHP_INT_MAX;
$nan = NAN;
for ($i = 0; $i < 200; $i++) {
    $nan_eq = $big == $nan;
    $nan_lt = $big < $nan;
    $nan_le = $big <= $nan;
    $nan_ne = $big != $nan;
}
check('PHP_INT_MAX == NAN under tracing', $nan_eq === false);
check('PHP_INT_MAX < NAN under tracing', $nan_lt === false);
check('PHP_INT_MAX <= NAN under tracing', $nan_le === false);
check('PHP_INT_MAX != NAN under tracing', $nan_ne === true);
?>
--EXPECT--
2 ** 63 - 1 == (float) (2 ** 63 - 1) (int on left): ok
2 ** 63 - 1 != (float) (2 ** 63 - 1) (int on left): ok
2 ** 63 - 1 < (float) (2 ** 63 - 1) (int on left): ok
2 ** 63 - 1 <= (float) (2 ** 63 - 1) (int on left): ok
2 ** 63 - 1 >= (float) (2 ** 63 - 1) (int on left): ok
2 ** 63 - 1 <=> (float) (2 ** 63 - 1) (int on left): ok
(float) (2 ** 63 - 1) == 2 ** 63 - 1 (float on left): ok
(float) (2 ** 63 - 1) < 2 ** 63 - 1 (float on left): ok
(float) (2 ** 63 - 1) <=> 2 ** 63 - 1 (float on left): ok
2 ** 53 + 1 == (float) (2 ** 53 + 1) (int on left): ok
2 ** 53 + 1 != (float) (2 ** 53 + 1) (int on left): ok
2 ** 53 + 1 < (float) (2 ** 53 + 1) (int on left): ok
2 ** 53 + 1 <= (float) (2 ** 53 + 1) (int on left): ok
2 ** 53 + 1 >= (float) (2 ** 53 + 1) (int on left): ok
2 ** 53 + 1 <=> (float) (2 ** 53 + 1) (int on left): ok
(float) (2 ** 53 + 1) == 2 ** 53 + 1 (float on left): ok
(float) (2 ** 53 + 1) < 2 ** 53 + 1 (float on left): ok
(float) (2 ** 53 + 1) <=> 2 ** 53 + 1 (float on left): ok
switch stays exact under tracing: ok
float-subject switch stays exact under tracing: ok
PHP_INT_MAX == NAN under tracing: ok
PHP_INT_MAX < NAN under tracing: ok
PHP_INT_MAX <= NAN under tracing: ok
PHP_INT_MAX != NAN under tracing: ok

--TEST--
bigint: msg_send() sends a boxed int as its exact decimal string
--EXTENSIONS--
sysvmsg
--FILE--
<?php

$queue = msg_get_queue(ftok(__FILE__, 'q'), 0600);

$big = 2 ** 80;
var_dump(msg_send($queue, 1, $big, false));
var_dump(msg_receive($queue, 1, $msg_type, 1024, $msg, false, MSG_IPC_NOWAIT));
var_dump($msg === '1208925819614629174706176');
var_dump($big == $msg);

$big = -(2 ** 80);
var_dump(msg_send($queue, 1, $big, false));
var_dump(msg_receive($queue, 1, $msg_type, 1024, $msg, false, MSG_IPC_NOWAIT));
var_dump($msg === '-1208925819614629174706176');
var_dump($big == $msg);

var_dump(msg_remove_queue($queue));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)

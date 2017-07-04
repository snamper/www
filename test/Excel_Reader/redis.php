<?php 

header('content-type:text/html;charset=utf-8');

$redis = new Redis();

$redis->connect('127.0.0.1',6379);

$redis->auth('redis-pass-word-');

return $redis;

?>
<?php
$redisObj = new Redis();
if ( ! @$redisObj->connect('127.0.0.1')) {
    $redisObj = false;
}

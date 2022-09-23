<?php
header('Content-Type:text/html;charset=utf-8');

$str = file_get_contents('php://input');

file_put_contents('notify.log', $str.PHP_EOL, 8);
?>
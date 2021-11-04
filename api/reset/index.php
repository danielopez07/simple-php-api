<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');

require dirname(__FILE__) . '/../../models/Reset.php';

$reset = new Reset();
$data  = $reset->reset_csv();

echo $data;

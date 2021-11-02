<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require dirname(__FILE__).'/../../models/Balance.php';

$balance = new Balance();
$data    = $balance->read();

echo json_encode([
    'status' => 200,
    'data'   => $data
]);
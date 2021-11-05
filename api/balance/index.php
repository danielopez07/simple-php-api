<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');

require dirname(__FILE__) . '/../../models/Balance.php';

$account_id = $_GET['account_id']; // we would need to sanitize this
if (!is_numeric($account_id)) {
    http_response_code(404);
    echo 0;
    die();
}

$balance = new Balance();
$data    = $balance->read($account_id);

if ($data === false) {
    http_response_code(404);
    echo 0;
    die();
}

echo $data;
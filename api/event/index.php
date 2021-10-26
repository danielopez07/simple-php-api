<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../models/Event.php';

$input        = json_decode(file_get_contents("php://input"));
var_dump($_POST);exit;
$event        = new Event();
$event->event = $input;
$data         = $event->create();

echo json_encode([
    'status' => 200,
    'data'   => $data
]);
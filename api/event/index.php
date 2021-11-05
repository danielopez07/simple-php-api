<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require dirname(__FILE__).'/../../models/Event.php';

$input = json_decode(file_get_contents('php://input'));
if (!isset($input->type)) {
    http_response_code(400);
    die('Type not specified');
}

$event = new Event();

switch ($input->type) {
    case 'deposit':
        $data = $event->deposit($input);
        break;

    case 'withdraw':
        $data = $event->withdraw($input);
        break;
    
    case 'transfer':
        $data = $event->transfer($input);
        break;
    
    default:
        http_response_code(404);
        die('Type not found');
        break;
}

http_response_code($data['status']);
die(json_encode($data['data']));
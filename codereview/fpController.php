<?php
die;
require_once __DIR__ . '/FalsePositive.php';

/**
 * This is false positive controller
 */
$operation = $_POST['type'];

$service = new FalsePositive();
switch ($operation) {
    case 'ADD':
        $code = $_POST['code'];
        $status = $service->markFalsePositive(base64_decode($code));
        header('Content-Type: application/json');
        echo json_encode(array('status' => $status));
        die;
        break;
    case 'DEL':
        break;
}

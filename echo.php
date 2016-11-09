<?php


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: OPTIONS, GET, POST ,PUT, DELETE, PATCH");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

header('Content-Type: application/json');

$_POST = json_decode(file_get_contents('php://input'), true);

echo json_encode($_POST);
die();

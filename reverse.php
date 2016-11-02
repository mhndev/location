<?php

include 'vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: OPTIONS, GET, POST ,PUT, DELETE, PATCH");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');



$googleClient = new \mhndev\location\GoogleGeocoder();

$googleClient->setHttpAgent(new \mhndev\location\GuzzleHttpAgent());

$result = $googleClient
    ->setLocale('fa-IR')
    ->reverse($_GET['lat'], $_GET['lon'], 3);


echo $result[2]['toString'];

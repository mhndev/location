<?php

include 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$googleClient = new \mhndev\location\GoogleGeocoder();

$googleClient->setHttpAgent(new \mhndev\location\GuzzleHttpAgent());

$result = $googleClient
    ->setLocale('fa-IR')
    ->geocode($_GET['query'], 1);


echo json_encode(['latitude'=>$result[0]['latitude'], 'longitude'=>$result[0]['longitude'] ]);

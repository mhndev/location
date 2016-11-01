<?php

include 'vendor/autoload.php';


$googleClient = new \mhndev\location\GoogleGeocoder();

$googleClient->setHttpAgent(new \mhndev\location\GuzzleHttpAgent());

$result = $googleClient
    ->setLocale('fa-IR')
    ->reverse($_GET['lat'], $_GET['lon'], 3);


echo $result[2]['toString'];

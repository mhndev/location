<?php

//https://maps.googleapis.com/maps/api/distancematrix/json?origins=35.733906,%2051.440589&destinations=35.681783,%2051.483411&key=AIzaSyAwmVos1B201fS2cR_2ahJ79YS2chfa84Y&traffic_model=pessimistic&departure_time=1478015497


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'vendor/autoload.php';


$estimate_client = new \mhndev\location\GoogleEstimate();

$result = $estimate_client
    ->setHttpAgent(new \mhndev\location\GuzzleHttpAgent())
    ->estimate($_GET['origin'], $_GET['destination'], 'optimistic');


echo json_encode($result);
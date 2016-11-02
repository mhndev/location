<?php


include 'vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build();
$deleteParams = ['index' => 'digipeyk'];
$response = $client->indices()->delete($deleteParams);

$intersections = json_decode(file_get_contents('data/tehran_intersection.json'), true)['RECORDS'];
$i = 1;

foreach($intersections as $intersection){

    echo $i."\n";

    $params['index'] = 'digipeyk';
    $params['id'] = $intersection['id'];
    $params['type'] = 'location';
    $params['body'] = $intersection;

    $response = $client->index($params);

    $i++;
}

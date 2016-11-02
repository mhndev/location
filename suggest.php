<?php
use Elasticsearch\ClientBuilder;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: OPTIONS, GET, POST ,PUT, DELETE, PATCH");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');



require 'vendor/autoload.php';

function getResponse($list){
    $result = [];
    foreach ($list as $record){
        $result[] = $record['_source'];
    }

    return $result;
}



$fields = ['search'];


$params = [
    'index' => 'digipeyk',
    'type' => 'location',
    'body' => [
        'query' => [
            'match' => [
                'slug' => '*'.$_GET['query'].'*'
            ]
        ]
    ]
];


$client = ClientBuilder::create()->build();

$response = $client->search($params);

echo json_encode(getResponse($response['hits']['hits']));

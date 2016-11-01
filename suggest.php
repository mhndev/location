<?php
use Elasticsearch\ClientBuilder;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
                'search' => $_GET['query']
            ]
        ]
    ]
];


$client = ClientBuilder::create()->build();

$response = $client->search($params);

echo json_encode(getResponse($response['hits']['hits']));


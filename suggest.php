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


$perPage = empty($_GET['perPage']) ? 10 : $_GET['perPage'];
$page = empty($_GET['page']) ? 1 : $_GET['page'];
$from = $perPage * ($page - 1);


$params = [
    'index' => 'digipeyk',
    'type' => 'location',
    'body' => [
        'query' => [
            'wildcard' => [
                'search' => '*'.$_GET['query'].'*'
            ]
        ],

        'from' => $from,
        'size' => $perPage
    ]
];



$client = ClientBuilder::create()->build();

$response = $client->search($params);



echo json_encode(paginate($response, $from, $perPage, $page));



function paginate($response, $from, $size, $page)
{
    $total = $response['hits']['total'];

    $result = $response['hits']['hits'];

    $to = min($total, $from + $size - 1);
    $next = ($total > $to) ? "/?page=" . ($page + 1) : null;
    $prev = ($from > 1) ? "/?page=" . ($page - 1) : null;




    $data['pagination']['total'] = $total;
    $data['pagination']['to'] = $to;
    $data['pagination']['from'] = $from;
    $data['pagination']['per_page'] = $size;
    $data['pagination']['current_page'] = $page;
    $data['pagination']['next_page_url'] = $next;
    $data['pagination']['prev_page_url'] = $prev;
    $data['data'] = getResponse($result);

    return $data;
}

<?php

use Elasticsearch\ClientBuilder;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'vendor/autoload.php';

echo time();

/*
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
}*/



$fields = ['search'];



/*"query": {
    "filtered": {
        "query": {
            "match": {
                "text": "quick brown fox"
        }
      },
      "filter": {
            "term": {
                "status": "published"
        }
      }
    }
  }
  */

$params = [
    'index' => 'digipeyk',
    'type' => 'location',
    'body' => [
        'query' => [
            'match' => [
                'search' => 'میرد'
            ]
        ]
    ]
];


$client = ClientBuilder::create()->build();

$response = $client->search($params);
Kint::dump($response);










//$intersections = json_decode(file_get_contents('data/tehran_intersection.json'),true)['RECORDS'];
//
//$result = [];
//$id = 1;
//
//
//foreach ($intersections as $record){
//
//    $record['search'] = implode(',',[
//        $record['first']['name'] . ' ' .$record['second']['name'],
//        $record['first']['slug'] . ' ' .$record['second']['slug'],
//        $record['first']['name'] . ' تقاطع ' .$record['second']['name'],
//        $record['first']['slug'] . ' taghato ' .$record['second']['slug'],
//
//    ]);
//
//
//    $result[] = $record;
//
//}


/*$converter = new ConvertFinglishToFarsi();
$result = $converter->Convert('man payam hastam?');*/

//$dataGateway = (new \mhndev\location\DataJsonGateway())->setDataSource('data/newProvince.json');
//$dataLocationSuggester = new \mhndev\location\DataLocationSuggester($dataGateway);
//$result = $dataLocationSuggester->suggest('Alb');
//Kint::dump($result);
//die();


//$data = ExcelService::toArray('data/Tehran_Mahale_Lat_Long.ods',[3])[3];
//$tr = new TranslateClient('fa', 'en');
//
//$i = 0;
//
//foreach ($data as $item) {
//
//    echo $i ."\n";
//
//    $result[] = [
//        'name' => $item['name'],
//        'slug' => $tr->translate($item['name']),
//        'latitude' => explode(',', $item['coordinates'])[0],
//        'longitude' => explode(',', $item['coordinates'])[1]
//
//    ];
//
//$i++;
//}

//$first = '';
//
//$i = 0;
//foreach ($data as $record){
//
//    echo $i."\n";
//
//    if(empty($record['second']) && empty($record['third']) ){
//
//        $first = $record['first'];
//        continue;
//    }
//
//
//    $result[] = [
//        'first' => ['name' => $first, 'slug' => $tr->translate($first ) ] ,
//        'second'=> ['name' => $record['second'], 'slug'=> $tr->translate( $record['second'] ) ],
//        'latitude'=> (!empty($record['third']) && array_key_exists(1, explode(',',$record['third'] ) ) ) ? explode(',',$record['third'])[0] : '',
//        'longitude' => (!empty($record['third']) && array_key_exists(1, explode(',',$record['third'] ) ) ) ? explode(',',$record['third'])[1] : '',
//        'type' => 'intersection'
//    ];
//
//    $i++;
//
//}






//
//foreach ($data as $area){
//
//    $keys = array_keys($area);
//
//    echo "next\n";
//    for($i=0; $i<count($area) -1; $i+=2){
//
//        if(!empty($area[$keys[$i]]) || !empty($area[$keys[$i+1]]) ){
//            $result[] = [
//                'area'      => $keys[$i][1],
//                'name'      => $area[$keys[$i]],
//                'slug'      => $tr->translate($area[$keys[$i]]),
//                'latitude'  => !empty($area[$keys[$i+1]]) ? explode(',', $area[$keys[$i+1]])[0] : '',
//                'longitude' => !empty($area[$keys[$i+1]]) ? explode(',', $area[$keys[$i+1]])[1] : ''
//            ];
//        }
//
//    }
//
//}
//
//$file = fopen('data/tehran_intersection2.json','w');
//
//fwrite($file, json_encode([ 'RECORDS'=>$result ] , JSON_UNESCAPED_UNICODE));

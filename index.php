<?php

use Stichoza\GoogleTranslate\TranslateClient;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require 'vendor/autoload.php';

$tr = new TranslateClient('fa', 'en');


$objPHPExcel = PHPExcel_IOFactory::load('locations.xlsx');



$centers = $objPHPExcel->getSheet(0)->toArray();
$res = parseCenters($centers, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('centers.json','w');
fwrite($file, $text);



$intersections = $objPHPExcel->getSheet(1)->toArray();
$res = parseIntersections($intersections, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('intersections.json','w');
fwrite($file, $text);


$sources = $objPHPExcel->getSheet(2)->toArray();
$res = parseSources($sources, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('sources.json','w');
fwrite($file, $text);



$embassies = $objPHPExcel->getSheet(3)->toArray();
$res = parseEmbassies($embassies, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('embassies.json','w');
fwrite($file, $text);


$squares = $objPHPExcel->getSheet(4)->toArray();
$res = parseSquares($squares, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('squares.json','w');
fwrite($file, $text);


$hospitals = $objPHPExcel->getSheet(5)->toArray();
$res = parseSquares($hospitals, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('hospitals.json','w');
fwrite($file, $text);


$malls = $objPHPExcel->getSheet(6)->toArray();
$res = parseMalls($malls, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('malls.json','w');
fwrite($file, $text);


$subways = $objPHPExcel->getSheet(7)->toArray();
$res = parseSubways($subways, $tr);
$text = json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
$file = fopen('subways.json','w');
fwrite($file, $text);












/**
 * @param $a1
 * @param $a2
 * @return array|string
 */
function createIntersectionsSearchString($a1, $a2)
{
    $results = [];

    foreach ($a1 as $a1Name){
        foreach($a2 as $a2Name){

            $results[] = $a1Name['name'] . ' - ' . $a2Name['name'];
            $results[] = $a1Name['slug'] . ' - ' . $a2Name['slug'];
        }
    }

    return $search = implode(',', $results);
}

/**
 * @param $array
 * @return string
 */
function createCentersSearchString($array)
{
    $result = [];

    foreach ($array as $item){
        $result[] = $item['name'];
        $result[] = $item['slug'];
    }

    return $search = implode(',', $result);
}


/**
 * @param $array
 * @return string
 */
function createMallsSearchString($array)
{
    $result = [];

    foreach ($array as $item){
        $result[] = $item['name'];
        $result[] = $item['slug'];
    }

    return $search = implode(',', $result);
}


/**
 * @param $array
 * @return string
 */
function createSquaresSearchString($array)
{
    $result = [];

    foreach ($array as $item){
        $result[] = $item['name'];
        $result[] = $item['slug'];
    }

    return $search = implode(',', $result);
}




/**
 * @param $delimiter
 * @param $string
 * @return array
 */
function explodeAndTrim($delimiter, $string)
{
    $result = [];

    $explode = explode($delimiter, $string);

    foreach ($explode as $item){
        if(!empty(trim($item) )){

            $result[] = trim($item);
        }
    }

    return $result;

}


/**
 * @param $delimiter
 * @param $string
 * @param TranslateClient $translator
 * @return array
 */
function explodeAndTrimAndTranslate($delimiter, $string , $translator)
{
    $result = [];

    $explode = explode($delimiter, $string);

    foreach ($explode as $item){
        if(!empty(trim($item) )){

            $result[] = [
                'name' => trim($item),
                'slug' => $translator->translate(trim($item))
            ];

        }
    }

    return $result;
}


/**
 * @param $centers
 * @param $translator
 * @return array
 */
function parseCenters($centers, $translator)
{
    $k = 0;
    $result = [];

    for( $i = 1 ; $i < count($centers); $i++ ){

        for ( $j = 0; $j< count($centers[$i]); $j+=2 ){

            if(!is_null($centers[$i][$j]) && !is_null($centers[$i][$j+1]) ){

                $result[$k] = [
                    'id'   => $k,
                    'area'     => $centers[0][$j],
                    'type' => 'center',
                    'location' => [
                        'lat' => trim(explode(',', $centers[$i][$j+1])[0]),
                        'lon' => trim(explode(',', $centers[$i][$j+1])[1])
                    ],
                    'names' => explodeAndTrimAndTranslate(',',$centers[$i][$j], $translator),
                ];

                $result[$k]['search'] = createCentersSearchString($result[$k]['names']);


                $k++;
            }

        }

        echo $i.EOL;

    }


    return $result;
}


/**
 * @param $intersections
 * @param $translator
 * @return array
 */
function parseIntersections($intersections, $translator)
{
    $results = [];
    $source = '';
    $k = 0;

    for($i=0; $i < count($intersections) ; $i++){

        for($j = 0; $j < 3 ; $j++){

            if(!is_null($intersections[$i][0] )){
                $source = $intersections[$i][0];
            }

            elseif(!is_null($intersections[$i][1]) && !is_null($intersections[$i][2])){

                $first_names = explodeAndTrimAndTranslate('-', $source, $translator);
                $second_names =  explodeAndTrimAndTranslate('-', $intersections[$i][1], $translator);


                $results[$k] = [
                    'type' => 'intersection',
                    'location' => [
                        'lat' => trim(explode(',', $intersections[$i][2])[0]),
                        'lon' => trim(explode(',', $intersections[$i][2])[1])
                    ],

                    'preview' => $source . ' - ' . explode('-',$intersections[$i][1])[0],

                    'first' => [
                        'type' => 'street',
                        'names' =>  $first_names
                    ],

                    'second' => [
                        'type' => 'street',
                        'names' => $second_names
                    ],

                    'search' => createIntersectionsSearchString($first_names, $second_names)
                ];

                $k++;

            }
        }

        echo $i.EOL;
    }


    return $results;

}


/**
 * @param $sources
 * @param $translator
 * @return array
 */
function parseSources($sources, $translator)
{
    $results = [];
    $k = 0;


    for( $i = 1; $i< count($sources);  $i++){

        if(!is_null($sources[$i][0] ) ){
            $area = $sources[$i][0];
        }

        if(!is_null($sources[$i][1])){
            $neighbourhood = $sources[$i][1];
        }

        if(!is_null($sources[$i][2])){
            $street = $sources[$i][2];
        }

        if(!is_null($sources[$i][3])){
            $alley = $sources[$i][3];
        }

        if(!is_null($sources[$i][4])){
            $locations = explode(',', $sources[$i][4]);
        }


        if(!empty($area) && !empty($neighbourhood) && !empty($street) && !empty($alley) && !empty($locations)){
            $first_names = explodeAndTrimAndTranslate('-', $street, $translator);
            $second_names = explodeAndTrimAndTranslate('-', $alley, $translator);

            $results[$k] = [
                'type' => 'intersection',
                'area' => $area,
                'neighbourhood' => [
                    'names' => explodeAndTrimAndTranslate('-', $neighbourhood, $translator)
                ],

                'first' => [
                    'type' => 'street',
                    'names' => $first_names
                ],

                'second' => [
                    'type' => 'alley',
                    'names' => $second_names
                ],

                'location' => [
                    'lat' => $locations[0],
                    'lon' => $locations[1]
                ],

                'search' => createIntersectionsSearchString($first_names, $second_names)
            ];


            $k++;
        }


        echo $i.EOL;


    }


    return $results;


}


/**
 * @param $embassies
 * @param $translator
 * @return array
 */
function parseEmbassies($embassies, $translator)
{
    $results = [];

    for ($i =1 ; $i<count($embassies); $i++){

        if(!empty($embassies[$i][1]) && !empty($embassies[$i][2]) ){

            $names = explodeAndTrimAndTranslate('-', $embassies[$i][1], $translator);

            $results[] = [
                'type' => 'embassy',
                'names' => $names,
                'location' => [
                    'lat'  => explode(',', $embassies[$i][2])[0],
                    'lon' => explode(',', $embassies[$i][2])[1]
                ],
                'search' => implode(',', $names)
            ];
        }

        echo $i.EOL;

    }


    return $results;
}


/**
 * @param $hospitals
 * @param $translator
 * @return array
 */
function parseHospitals($hospitals, $translator)
{
    $results = [];

    for ($i =1 ; $i<count($hospitals); $i++){

        if(!empty($hospitals[$i][1]) && !empty($hospitals[$i][2]) ){

            $names = explodeAndTrimAndTranslate('-', $hospitals[$i][1], $translator);

            $results[] = [
                'type' => 'hospital',
                'names' => $names,
                'location' => [
                    'lat'  => explode(',', $hospitals[$i][2])[0],
                    'lon' => explode(',', $hospitals[$i][2])[1]
                ],
                'search' => implode(',', $names)
            ];
        }

        echo $i.EOL;

    }


    return $results;
}


/**
 * @param $squares
 * @param $translator
 * @return array
 */
function parseSquares($squares , $translator)
{
    $results = [];

    for ($i =1 ; $i<count($squares); $i++){

        if(!empty($squares[$i][1]) && !empty($squares[$i][2]) ){

            $names = explodeAndTrimAndTranslate('-', $squares[$i][1], $translator);

            $results[] = [
                'type' => 'square',
                'names' => $names,
                'location' => [
                    'lat'  => explode(',', $squares[$i][2])[0],
                    'lon' => explode(',', $squares[$i][2])[1]
                ],
                'search' => createSquaresSearchString($names)
            ];
        }

        echo $i.EOL;

    }

    return $results;
}


/**
 * @param $malls
 * @param $translator
 * @return array
 */
function parseMalls($malls, $translator)
{
    $results = [];

    for ($i =1 ; $i<count($malls); $i++){

        if(!empty($malls[$i][1]) && !empty($malls[$i][2]) ){

            $names = explodeAndTrimAndTranslate('-', $malls[$i][1], $translator);

            var_dump($names);
            die();

            $results[] = [
                'type' => 'mall',
                'names' => $names,
                'location' => [
                    'lat'  => explode(',', $malls[$i][2])[0],
                    'lon' => explode(',', $malls[$i][2])[1]
                ],
                'search' => createMallsSearchString($names)
            ];
        }

        echo $i.EOL;

    }

    return $results;
}


/**
 * @param $subways
 * @param $
 * @param $translator
 * @return array
 */
function parseSubways($subways , $translator)
{
    $results = [];

    $istgah = 'ایستگاه';
    $istgah_metro = 'ایستگاه مترو';
    for ($i =1 ; $i<count($subways); $i++){

        if(!empty($subways[$i][0]) && !empty($subways[$i][1]) ){

            $names = array_merge(
                $preview = explodeAndTrim('-', $subways[$i][0]),
                [
                    $istgah . ' ' . $preview[0],
                    $istgah_metro . ' ' . $preview[0],
                ]
            );


            $results[] = [
                'type' => 'subway',
                'names' => $names,
                'preview' => explodeAndTrim('-', $subways[$i][0])[0],

                'location' => [
                    'lat'  => explode(',', $subways[$i][1])[0],
                    'lon' => explode(',', $subways[$i][1])[1]
                ],

                'search' => implode(',', $names)
            ];
        }

        echo $i.EOL;

    }

    return $results;
}





/*header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: OPTIONS, GET, POST ,PUT, DELETE, PATCH");
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');


require 'vendor/autoload.php';


$file = file_get_contents('data/tehran_squares.json');

$data = json_decode($file, true)['RECORDS'];

$result = [];

foreach ($data as $record){
    $record['type'] = 'square';

    $result[] = $record;
}


$file = fopen('data/tehran_squares.json','w');

fwrite($file, json_encode([ 'RECORDS'=>$result ] , JSON_UNESCAPED_UNICODE));


Kint::dump($data);
die();



$estimate_client = new \mhndev\location\GoogleEstimate();


$result = $estimate_client->setHttpAgent(new \mhndev\location\GuzzleHttpAgent())->estimate('35.733906, 51.440589', '35.681783, 51.483411', 'optimistic');

echo json_encode($result);*/





















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

//$params = [
//    'index' => 'digipeyk',
//    'type' => 'location',
//    'body' => [
//        'query' => [
//            'match' => [
//                'search' => 'میرد'
//            ]
//        ]
//    ]
//];
//
//
//$client = ClientBuilder::create()->build();
//
//$response = $client->search($params);
//Kint::dump($response);










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

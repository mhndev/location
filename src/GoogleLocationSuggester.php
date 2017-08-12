<?php
namespace mhndev\location;

use mhndev\location\Interfaces\iHttpAgent;
use mhndev\location\Interfaces\iLocationSuggester;

/**
 * Class GoogleLocationSuggester
 * @package mhndev\location
 */
class GoogleLocationSuggester implements iLocationSuggester
{


    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var iHttpAgent
     */
    protected $httpAgent;


    /**
     * GoogleLocationSuggester constructor.
     */
    public function __construct()
    {
        $this->httpAgent = new GuzzleHttpAgent();

        $this->apiKey = 'AIzaSyAwmVos1B201fS2cR_2ahJ79YS2chfa84Y';
    }

    /**
     * @param $query
     * @return mixed
     */
    function suggest($query)
    {
        $key = $this->apiKey;

        $result = $this->httpAgent->GET("https://maps.googleapis.com/maps/api/place/autocomplete/json?key=".$key."&radius=20000&location=35.6892,51.3890&strictbounds&input=".$query);

        $addresses = json_decode($result->getBody()->getContents(), true);

        return $addresses['predictions'];
    }

}

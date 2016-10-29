<?php
namespace mhndev\location;

use mhndev\location\Interfaces\iHttpAgent;
use mhndev\location\Interfaces\iLocationSuggester;

class ReyhoonLocationSuggester implements iLocationSuggester
{

    /**
     * @var iHttpAgent
     */
    protected $httpAgent;


    public function __construct()
    {
        $this->httpAgent = new GuzzleHttpAgent();
    }

    /**
     * @param $query
     * @return mixed
     */
    function suggest($query)
    {
        $result = $this->httpAgent->GET("https://www.reyhoon.com/location/query?query=".$query);

        $addresses = json_decode($result->getBody()->getContents(), true);


        return $addresses['suggestions'];
    }

}

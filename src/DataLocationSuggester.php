<?php

namespace mhndev\location;

use mhndev\location\Interfaces\iDataGateway;
use mhndev\location\Interfaces\iLocationSuggester;

/**
 * Class DataLocationSuggester
 * @package mhndev\location
 */
class DataLocationSuggester implements iLocationSuggester
{


    /**
     * @var iDataGateway
     */
    protected $dataGateway;

    /**
     * DataLocationSuggester constructor.
     * @param iDataGateway $gateway
     */
    public function __construct(iDataGateway $gateway)
    {
        $this->dataGateway = $gateway;
    }


    /**
     * @param $query
     * @return mixed
     */
    function suggest($query)
    {
        return $this->dataGateway->query($query);
    }

}

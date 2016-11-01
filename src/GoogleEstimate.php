<?php

namespace mhndev\location;

use mhndev\location\Interfaces\iEstimate;
use mhndev\location\Interfaces\iHttpAgent;

/**
 * Class GoogleEstimate
 * @package mhndev\location
 */
class GoogleEstimate implements iEstimate
{


    /**
     * @var iHttpAgent
     */
    protected $httpAgent;


    /**
     * @var boolean
     */
    private $useSsl = true;


    /**
     * @var string
     */
    private $locale;



    private $api_key = 'AIzaSyAwmVos1B201fS2cR_2ahJ79YS2chfa84Y';

    const ENDPOINT_URL = 'http://maps.googleapis.com/maps/api/distancematrix/json';


    const ENDPOINT_URL_SSL = 'https://maps.googleapis.com/maps/api/distancematrix/json';


    /**
     * @param $origin
     * @param $destination
     * @param $departure_time
     * @param null $traffic_model
     * @return mixed
     * @throws \Exception
     */
    function estimate($origin, $destination, $traffic_model = null, $departure_time = null)
    {
        $query = 'key='.$this->api_key.'origin='.$origin.'&destination='.$destination;

        if($traffic_model && $departure_time == null){
            $departure_time = time();
        }

        if($traffic_model){
            $query .= '&departure_time='.$departure_time.'&traffic_model='.$traffic_model;
        }

        $query = self::ENDPOINT_URL.'?'.$query;
        $query = sprintf('%s&language=%s', $query, $this->getLocale());
        $response = $this->httpAgent->GET($query);


        \Kint::dump($response->getBody()->getContents());
        die();
    }


    /**
     * @param bool $useSsl
     */
    public function setUseSsl($useSsl = true)
    {
        $this->useSsl = $useSsl;
    }


    /**
     * @param iHttpAgent $httpAgent
     * @return $this
     */
    public function setHttpAgent(iHttpAgent $httpAgent)
    {
        $this->httpAgent = $httpAgent;

        return $this;
    }

    /**
     * @return string
     */
    function getLocale()
    {
        if($this->locale){
            return $this->locale;
        }
        else{
            $this->setLocale('en-US');

            return $this->locale;
        }
    }

    /**
     * @param $locale
     * @return $this
     */
    function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

}
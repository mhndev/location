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



    private $api_key = 'AIzaSyADitw3_YLb5RwfJTjLlvh5FBy8osBlesY';

    const ENDPOINT_URL = 'http://maps.googleapis.com/maps/api/distancematrix/json';


    const ENDPOINT_URL_SSL = 'https://maps.googleapis.com/maps/api/distancematrix/json';



    //https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&key=AIzaSyADitw3_YLb5RwfJTjLlvh5FBy8osBlesY&origin=35.733906,%2051.440589&destinations=35.681783,%2051.483411&departure_time=1478031254&traffic_model=optimistic&language=en-US

    //https://maps.googleapis.com/maps/api/distancematrix/json?origins=35.733906,%2051.440589&destinations=35.681783,%2051.483411&key=AIzaSyAwmVos1B201fS2cR_2ahJ79YS2chfa84Y&traffic_model=pessimistic&departure_time=1478015497
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
        $query = 'key='.$this->api_key.'&origins='.$origin.'&destinations='.$destination;

        if($traffic_model && $departure_time == null){
            $departure_time = time();
        }

        if($traffic_model){
            $query .= '&departure_time='.$departure_time.'&traffic_model='.$traffic_model;
        }


        $query = self::ENDPOINT_URL_SSL.'?'.$query;
        $query = sprintf('%s&language=%s', $query, $this->getLocale());



        $response = $this->httpAgent->GET($query);


        $result = json_decode($response->getBody()->getContents(), true);

        $return = [
            'origin' => $result['origin_addresses'][0],
            'destination' => $result['destination_addresses'][0],
            'distance' => $result['rows'][0]['elements'][0]['distance']['text'],
            'duration' => $result['rows'][0]['elements'][0]['duration']['text'],
            'duration_in_traffic' => $result['rows'][0]['elements'][0]['duration_in_traffic']['text']
        ];

        return $return;
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

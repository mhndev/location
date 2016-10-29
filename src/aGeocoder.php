<?php

namespace mhndev\location;
use mhndev\location\Interfaces\iGeocoder;

/**
 * Class aGeocoder
 * @package mhndev\location
 */
abstract class aGeocoder implements iGeocoder
{
    /**
     * @param $address
     * @return mixed
     */
    abstract function geocode($address);

    /**
     * @param $lat
     * @param $lng
     * @return mixed
     */
    abstract function reverse($lat, $lng);

}

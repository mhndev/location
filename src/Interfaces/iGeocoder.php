<?php

namespace mhndev\location\Interfaces;

/**
 * Interface iGeocoder
 * @package mhndev\location\Interfaces
 */
interface iGeocoder
{

    /**
     * @param $address
     * @return mixed
     */
    function geocode($address);


    /**
     * @param $lat
     * @param $lng
     * @return mixed
     */
    function reverse($lat, $lng);
}

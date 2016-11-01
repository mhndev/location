<?php

namespace mhndev\location\Interfaces;

/**
 * Interface iEstimate
 * @package mhndev\location\Interfaces
 */
interface iEstimate
{

    /**
     * @param $origin
     * @param $destination
     * @param $departure_time
     * @param null $traffic_model
     * @return mixed
     */
    function estimate($origin, $destination, $departure_time = null, $traffic_model = null);
}

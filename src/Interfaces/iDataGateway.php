<?php

namespace mhndev\location\Interfaces;

/**
 * Interface iDataGateway
 * @package mhndev\location\Interfaces
 */
interface iDataGateway
{


    /**
     * @param $query
     * @return mixed
     */
    function query($query);

}

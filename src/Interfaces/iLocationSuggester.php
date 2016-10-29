<?php
namespace mhndev\location\Interfaces;

/**
 * Interface iLocationSuggester
 * @package mhndev\location\Interfaces
 */
interface iLocationSuggester
{
    /**
     * @param $query
     * @return mixed
     */
    function suggest($query);

}

<?php
namespace mhndev\location\Interfaces;

/**
 * Interface iLocaleAware
 * @package mhndev\location\Interfaces
 */
interface iLocaleAware
{

    /**
     * @return string
     */
    function getLocale();


    /**
     * @param $locale
     * @return $this
     */
    function setLocale($locale);
}

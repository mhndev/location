<?php
namespace mhndev\location\Models;

/**
 * Class Coordinates
 * @package mhndev\location\Models
 */
final class Coordinates
{
    /**
     * @var double
     */
    private $latitude;

    /**
     * @var double
     */
    private $longitude;

    /**
     * @param double $latitude
     * @param double $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude.
     *
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns the longitude.
     *
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}

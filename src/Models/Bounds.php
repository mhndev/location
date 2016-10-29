<?php
namespace mhndev\location\Models;

/**
 * Class Bounds
 * @package mhndev\location\Models
 */
final class Bounds
{
    /**
     * @var double
     */
    private $south;

    /**
     * @var double
     */
    private $west;

    /**
     * @var double
     */
    private $north;

    /**
     * @var double
     */
    private $east;

    /**
     * @param double $south
     * @param double $west
     * @param double $north
     * @param double $east
     */
    public function __construct($south, $west, $north, $east)
    {
        $this->south = $south;
        $this->west  = $west;
        $this->north = $north;
        $this->east  = $east;
    }

    /**
     * Returns the south bound.
     *
     * @return double
     */
    public function getSouth()
    {
        return $this->south;
    }

    /**
     * Returns the west bound.
     *
     * @return double
     */
    public function getWest()
    {
        return $this->west;
    }

    /**
     * Returns the north bound.
     *
     * @return double
     */
    public function getNorth()
    {
        return $this->north;
    }

    /**
     * Returns the east bound.
     *
     * @return double
     */
    public function getEast()
    {
        return $this->east;
    }

    /**
     * Returns whether or not bounds are defined
     *
     * @return bool
     */
    public function isDefined()
    {
        return !empty($this->south) && !empty($this->east) && !empty($this->north) && !empty($this->west);
    }

    /**
     * Returns an array with bounds.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'south' => $this->getSouth(),
            'west'  => $this->getWest(),
            'north' => $this->getNorth(),
            'east'  => $this->getEast()
        ];
    }
}

<?php
namespace mhndev\location\Models;

/**
 * Class AdminLevel
 * @package mhndev\location\Models
 */
final class AdminLevel
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @param int    $level
     * @param string $name
     * @param string $code
     */
    public function __construct($level, $name, $code)
    {
        $this->level = $level;
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * Returns the administrative level
     *
     * @return int Level number [1,5]
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Returns the administrative level name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the administrative level short name.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns a string with the administrative level name.
     *
     * @return string
     */
    public function toString()
    {
        return $this->getName();
    }

    /**
     * Returns a string with the administrative level name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}

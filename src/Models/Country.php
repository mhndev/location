<?php
namespace mhndev\location\Models;

/**
 * Class Country
 * @package mhndev\location\Models
 */
final class Country
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $name
     * @param string $code
     */
    public function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * Returns the country name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the country ISO code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns a string with the country name.
     *
     * @return string
     */
    public function toString()
    {
        return $this->getName();
    }

    /**
     * Returns a string with the country name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}

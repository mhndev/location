<?php

namespace mhndev\location\Models;
use mhndev\location\Exception\CollectionIsEmpty;

/**
 * Class AddressCollection
 * @package mhndev\location\Models
 */
final class AddressCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Address[]
     */
    private $addresses;

    /**
     * @param Address[] $addresses
     */
    public function __construct(array $addresses = [])
    {
        $this->addresses = array_values($addresses);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->addresses);
    }

    /**
     * @return Address
     */
    public function first()
    {
        if (empty($this->addresses)) {
            throw new CollectionIsEmpty;
        }

        return reset($this->addresses);
    }

    /**
     * @param $offset
     * @param null $length
     * @return Address[]
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->addresses, $offset, $length);
    }

    /**
     * @param $index
     * @return bool
     */
    public function has($index)
    {
        return isset($this->addresses[$index]);
    }

    /**
     * @param $index
     * @return Address
     */
    public function get($index)
    {
        if (!isset($this->addresses[$index])) {
            throw new \OutOfBoundsException(sprintf('The index "%s" does not exist in this collection.', $index));
        }

        return $this->addresses[$index];
    }

    /**
     * @return Address[]
     */
    public function all()
    {
        return $this->addresses;
    }
}

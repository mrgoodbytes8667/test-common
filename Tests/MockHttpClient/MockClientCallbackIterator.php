<?php

namespace Bytes\Tests\Common\MockHttpClient;

use Iterator;

/**
 * Class MockClientCallbackIterator.
 */
abstract class MockClientCallbackIterator implements Iterator
{
    /**
     * @var int
     */
    private $position = 0;

    private $array;

    /**
     * MockClientCallbackIterator constructor.
     *
     * @param null $array
     */
    public function __construct($array = null)
    {
        $this->position = 0;
        $this->setArray($array ?? []);
    }

    /**
     * @param mixed $array
     *
     * @return $this
     */
    public function setArray($array): static
    {
        $this->array = $array;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function add($value): static
    {
        $this->array[] = $value;

        return $this;
    }

    /**
     * Return the current element.
     *
     * @see https://php.net/manual/en/iterator.current.php
     *
     * @return mixed can return any type
     */
    public function current()
    {
        return $this->array[$this->position];
    }

    /**
     * Move forward to next element.
     *
     * @see https://php.net/manual/en/iterator.next.php
     *
     * @return void any returned value is ignored
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element.
     *
     * @see https://php.net/manual/en/iterator.key.php
     *
     * @return string|float|int|bool|null scalar on success, or null on failure
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid.
     *
     * @see https://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *              Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->array[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @see https://php.net/manual/en/iterator.rewind.php
     *
     * @return void any returned value is ignored
     */
    public function rewind()
    {
        $this->position = 0;
    }
}

<?php

/*
 *  restclient-php is a full-featured REST client for PUMA and/or
 *  BibSonomy.
 *
 *  Copyright (C) 2015
 *
 *  Knowledge & Data Engineering Group,
 *  University of Kassel, Germany
 *  http://www.kde.cs.uni-kassel.de/
 *
 *  HothoData GmbH, Germany
 *  http://www.academic-puma.de
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AcademicPuma\RestClient\Util\Collection;

/**
 * ArrayList
 *
 * @author Sebastian Böttger
 */
class ArrayList implements Collection
{
    /**
     * @var int start index
     */
    private $start;
    /**
     * @var int end index
     */
    private $end;
    /**
     * @var string pagination
     */
    private $next;

    /**
     * @var array
     */
    protected $array;

    public function __construct(array $data = [])
    {
        $this->array = $data;
    }

    /**
     * @return int
     */
    public function getStart(): ?int
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return ArrayList
     */
    public function setStart(int $start): ArrayList
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return int
     */
    public function getEnd(): ?int
    {
        return $this->end;
    }

    /**
     * @param int $end
     * @return ArrayList
     */
    public function setEnd(int $end): ArrayList
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return string
     */
    public function getNext(): ?string
    {
        return $this->next;
    }

    /**
     * @param string $next
     * @return ArrayList
     */
    public function setNext(string $next): ArrayList
    {
        $this->next = $next;
        return $this;
    }

    /**
     * @return array
     */
    public function getArray(): ?array
    {
        return $this->array;
    }

    /**
     * @param array $array
     * @return ArrayList
     */
    public function setArray(array $array): ArrayList
    {
        $this->array = $array;
        return $this;
    }

    public function toArray(): ?array
    {
        return $this->array;
    }

    public function clear(): ArrayList
    {
        $this->array = [];
        return $this;
    }

    public function get($key)
    {
        return $this->array[$key] ?? null;
    }

    public function set($key, $value): ArrayList
    {
        $this->array[$key] = $value;
        return $this;
    }

    public function add($key, $value): ArrayList
    {
        if (!array_key_exists($key, $this->array)) {
            $this->array[$key] = $value;
        } elseif (is_array($this->array[$key])) {
            $this->array[$key][] = $value;
        } else {
            $this->array[$key] = [$this->array[$key], $value];
        }

        return $this;
    }


    public function remove($key): ArrayList
    {
        unset($this->array[$key]);
        return $this;
    }

    /**
     *
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key): bool
    {
        return array_key_exists($key, $this->array);
    }

    /**
     *
     * @param string $value
     *
     * @return false|int|string
     */
    public function hasValue(string $value)
    {
        return array_search($value, $this->array, true);
    }

    /**
     *
     * @param array $data
     *
     * @return ArrayList
     */
    public function replace(array $data): ArrayList
    {
        $this->array = $data;
        return $this;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->array);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->array[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->array[$offset] = $value;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->array[$offset]);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->array[$offset]);
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function isEmpty(): bool
    {
        return $this->count() <= 0;
    }

}

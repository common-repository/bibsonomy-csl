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

use AcademicPuma\RestClient\Config\Sorting;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Model\Tag;

/**
 * Short description
 *
 * @since 29.06.15
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class TagComparator
{
    const COMPARABLE_ATTRIBUTES = ['name', 'usercount', 'globalcount'];

    protected $sortingKey;

    protected $sortingOrder;

    public function __construct($sortingKey, $sortingOrder)
    {
        $this->sortingKey = $sortingKey;
        $this->sortingOrder = $sortingOrder;
    }

    public function compare(Tag $a, Tag $b): int
    {
        if (!in_array($this->sortingKey, self::COMPARABLE_ATTRIBUTES)) {
            throw new Model\Exceptions\InvalidSortingTypeException("Attribute '.$this->sortingKey.' is not a valid
                sorting key for Tags. Use " . implode(', ', self::COMPARABLE_ATTRIBUTES) . " instead.");
        }

        return ($this->sortingOrder === Sorting::ORDER_ASC) ? $a->compare($b, $this->sortingKey) :
            $b->compare($a, $this->sortingKey);
    }
}
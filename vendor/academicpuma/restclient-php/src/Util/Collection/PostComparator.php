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
use AcademicPuma\RestClient\Model\Bibtex;
use AcademicPuma\RestClient\Model\Bookmark;
use AcademicPuma\RestClient\Model\Exceptions\InvalidSortingTypeException;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Util\SortingUtils;

/**
 * Comparator for sort function of <code>Model\Posts</code>
 *
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class PostComparator
{

    const COMPARABLE_ATTRIBUTES = ['author', 'title', 'year', 'month', 'entrytype', 'dblp'];

    protected $sortingKey;

    protected $sortingOrder;

    public function __construct($sortingKey, $sortingOrder = Sorting::ORDER_ASC)
    {
        $this->sortingKey = $sortingKey;
        $this->sortingOrder = $sortingOrder;
    }

    /**
     * @param Post $a
     * @param Post $b
     *
     * @return int
     * @throws InvalidSortingTypeException
     */
    public function compare(Post $a, Post $b): int
    {
        if ($a->getResource() instanceof Bookmark && $this->sortingKey !== 'title') {
            throw new InvalidSortingTypeException("Attribute '.$this->sortingKey.' is not a valid
                sorting key for Bookmarks. Use 'title' instead.");
        } else if (!in_array($this->sortingKey, self::COMPARABLE_ATTRIBUTES)) {
            throw new InvalidSortingTypeException("Attribute '.$this->sortingKey.' is not a valid
                sorting key for Bibtex. Use " . implode(', ', self::COMPARABLE_ATTRIBUTES) . " instead.");
        }

        if ($this->sortingKey === 'dblp') {
            $bibtex_a = $a->getResource();
            $bibtex_b = $b->getResource();

            if ($a->getResource() instanceof Bibtex && $b->getResource() instanceof Bibtex) {
                return SortingUtils::compareDBLPFormat($bibtex_a, $bibtex_b);
            }
        }

        return ($this->sortingOrder === Sorting::ORDER_ASC) ?
            $a->getResource()->compare($b->getResource(), $this->sortingKey) :
            $b->getResource()->compare($a->getResource(), $this->sortingKey);

    }
}
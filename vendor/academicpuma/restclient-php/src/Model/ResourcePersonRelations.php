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

namespace AcademicPuma\RestClient\Model;


use AcademicPuma\RestClient\Config\Sorting;
use AcademicPuma\RestClient\Util\Collection\ArrayList;
use AcademicPuma\RestClient\Util\Collection\Comparator;


/**
 * Class ResourcePersonRelations
 * A list of Model/ResourcePersonRelations objects,
 * usually used to represent relations between a person and their posts.
 * @package AcademicPuma\RestClient\Model
 * @author kchoong
 */
class ResourcePersonRelations extends ArrayList implements ModelObject
{

    public function __toString()
    {
        return '[' . implode(',', $this->array) . ']';
    }

    /**
     * Sorts the list of person objects
     * @param string $order sorting order (allowed values: asc|desc)
     *
     * @return $this
     */
    public function sort(string $order = Sorting::ORDER_ASC): ResourcePersonRelations
    {
        $comparator = new Comparator($order);
        usort($this->array, [$comparator, "compare"]);
        return $this;
    }

}
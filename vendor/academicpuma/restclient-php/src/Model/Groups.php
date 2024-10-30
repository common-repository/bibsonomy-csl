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
 * Description of Posts
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class Groups extends ArrayList implements ModelObject
{

    public function __toString()
    {
        return '[' . implode(', ', $this->array) . ']';
    }

    /**
     * @param string $order sorting order (allowed values: asc|desc)
     *
     * @return $this
     */
    public function sort(string $order = Sorting::ORDER_ASC): Groups
    {
        $comparator = new Comparator($order);
        usort($this->array, [$comparator, "compare"]);
        return $this;
    }
}

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

namespace AcademicPuma\RestClient\Util;

use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Config\TagOrder;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;

/**
 * Contains helper methods to quickly validate common parameters of RESTClient methods.
 *
 * @author Florian Fassing <florian.fassing@gmail.com>
 */
class ParameterCheck
{

    /**
     * @param $resourceType
     * @return bool
     * @throws UnsupportedOperationException
     */
    public static function checkResourcetype($resourceType): bool
    {
        if (strtolower($resourceType) === strtolower(Resourcetype::BOOKMARK) ||
            strtolower($resourceType) === strtolower(Resourcetype::BIBTEX) ||
            strtolower($resourceType) === strtolower(Resourcetype::GOLD_STANDARD_BOOKMARK) ||
            strtolower($resourceType) === strtolower(Resourcetype::GOLD_STANDARD_PUBLICATION)) {

            return true;

        } else {

            throw new UnsupportedOperationException('Resourcetype ' . $resourceType
                . ' is not available. It has to be ' . Resourcetype::BOOKMARK . ' or '
                . Resourcetype::BIBTEX . '.');
        }
    }

    /**
     * @param $grouping
     * @return bool
     * @throws UnsupportedOperationException
     */
    public static function checkGrouping($grouping): bool
    {
        if ($grouping === Grouping::USER || $grouping === Grouping::GROUPING
            || $grouping === Grouping::GROUP || $grouping == Grouping::VIEWABLE
            || $grouping === Grouping::PERSON) {

            return true;

        } else {
            throw new UnsupportedOperationException("Grouping '" . $grouping
                . "' is not available.");
        }
    }

    /**
     * @param $grouping
     * @return bool
     * @throws UnsupportedOperationException
     */
    public static function checkGroupingWithoutGroups($grouping): bool
    {
        if ($grouping === Grouping::USER || $grouping === Grouping::GROUPING_VALUE_ALL) {

            return true;

        } else if ($grouping === Grouping::GROUP) {

            throw new UnsupportedOperationException("Grouping " . Grouping::GROUP
                . "is not implemented yet");
        } else {

            throw new UnsupportedOperationException("Grouping " . $grouping
                . " is not available.");
        }
    }

    /**
     * @param $tags
     * @return bool
     * @throws UnsupportedOperationException
     */
    public static function checkTags($tags): bool
    {
        if (!empty($tags) && is_array($tags)) {

            return true;

        } else {

            throw new UnsupportedOperationException("The \$tags param must be filled and"
                . "has to be an array.");
        }
    }

    /**
     * @param $order
     * @return bool
     * @throws UnsupportedOperationException
     */
    public static function checkTagOrder($order): bool
    {
        if ($order === TagOrder::FREQUENCY || $order === TagOrder::ALPHANUMERIC) {
            return true;
        }

        throw new UnsupportedOperationException("Tag order '" . $order . "'' is not available.");
    }
}

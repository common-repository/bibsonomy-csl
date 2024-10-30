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

use AcademicPuma\RestClient\Config;

/**
 * Description of UrlBuilder
 *
 * @author Florian Fassing
 */
class UrlBuilder
{

    const PATH_PART_API = "api";

    public function buildUrl(array $path, array $params)
    {

        $url = self::PATH_PART_API;

        // Iterate through the parts of the url and add them.
        foreach ($path as $pathPart) {
            if (is_array($pathPart)) {
                $url .= '/' . rawurlencode(implode(" ", $pathPart));
            } else {
                $url .= '/' . rawurlencode($pathPart);
            }
        }

        if (!empty($params)) {
            $url .= '?';

            // Get last element of the params to get rid of the last &.
            end($params);
            $lastKey = key($params);

            // Iterate through the parameters of the url and add if any value is given.
            foreach ($params as $key => $param) {
                // Does the entry have any value?
                if (!empty($params[$key]) || ($key === Config\RESTConfig::START_PARAM && $param === 0)) {

                    $urlparam = is_array($param) ? rawurlencode(implode(' ', $param)) : rawurlencode($param);
                    $url .= rawurlencode($key) . '=' . $urlparam;

                    // Only add another ampersand if it's not the last parameter.
                    if (!($key === $lastKey)) {
                        $url .= '&';
                    }
                }
            }
        }

        return $url;
    }
}

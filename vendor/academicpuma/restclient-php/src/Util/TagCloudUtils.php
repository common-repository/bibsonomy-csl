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

use AcademicPuma\RestClient\Model;

class TagCloudUtils
{


    /**
     *
     * @param \AcademicPuma\RestClient\Model\Tags $tags
     * @param string $targetUrl
     * @param float $min
     * @param float $max
     *
     * @return string rendered TagCloud
     */
    public static function simpleTagCloud(Model\Tags $tags, $targetUrl, $min, $max)
    {

        /** @var Model\Tag $first $first */
        $first = $tags->get(0);
        $maxCount = $first->getUsercount();
        $out = [];

        foreach ($tags as $tag) {
            /** @var Model\Tag $tag */

            $count = $tag->getUsercount();

            $size = ($count / $maxCount) * ($max - $min) + $min;

            $out[$tag->getName()] = '<a href="' . $targetUrl . '?tag=' . urlencode($tag->getName()) . '" style="font-size: ' . sprintf("%01.2f", $size) . 'em;">' . $tag->getName() . '</a> ';
        }

        sort($out);

        return implode('', array_values($out));

    }
}

?>

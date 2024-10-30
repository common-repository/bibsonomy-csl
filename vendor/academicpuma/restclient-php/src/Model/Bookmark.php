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

/**
 * Description of Bookmark
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class Bookmark extends Resource
{

    /**
     * An {@link URL} pointing to some website.
     * @var string
     */
    private $url;

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return Bookmark
     */
    public function setUrl(?string $url): Bookmark
    {
        $this->url = $url;
        return $this;
    }

    public function __toString()
    {
        return 'bookmark/' . parent::__toString();
    }

    /**
     * @param Bookmark $b
     * @param string $sortKey
     *
     * @return integer
     */
    public function compare($b, string $sortKey): int
    {
        return strcmp($this->getTitle(), $b->getTitle());
    }
}

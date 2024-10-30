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


use AcademicPuma\RestClient\Util\Collection\Comparable;

class Document implements ModelObject, Comparable
{

    /**
     * @var string $filename
     */
    private $filename;

    /**
     * @var string $md5hash
     */
    private $md5hash;

    /**
     * @var string $userName
     */
    private $userName;

    /**
     * @var string $href
     */
    private $href;

    /**
     * @return string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Document
     */
    public function setFilename(?string $filename): Document
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getMd5hash(): ?string
    {
        return $this->md5hash;
    }

    /**
     * @param string|null $md5hash
     * @return Document
     */
    public function setMd5hash(?string $md5hash): Document
    {
        $this->md5hash = $md5hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string|null $userName
     * @return Document
     */
    public function setUserName(?string $userName): Document
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * @param string|null $href
     * @return Document
     */
    public function setHref(?string $href): Document
    {
        $this->href = $href;
        return $this;
    }

    public function __toString()
    {
        return $this->filename;
    }

    public function compare(Comparable $b): int
    {
        return strcmp($this->getFilename(), $b->getFilename());
    }

}
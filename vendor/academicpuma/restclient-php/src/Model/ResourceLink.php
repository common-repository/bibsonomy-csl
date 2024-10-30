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
 * Class ResourceLink
 * @package AcademicPuma\RestClient\Model
 * @author kchoong
 */
class ResourceLink implements ModelObject
{
    /**
     * @var string the interhash of a resource
     */
    private $interHash;
    /**
     * @var string the intrahash of a resource
     */
    private $intraHash;

    /**
     * @return string
     */
    public function getInterHash(): ?string
    {
        return $this->interHash;
    }

    /**
     * @param string|null $interHash
     * @return ResourceLink
     */
    public function setInterHash(?string $interHash): ResourceLink
    {
        $this->interHash = $interHash;
        return $this;
    }

    /**
     * @return string
     */
    public function getIntraHash(): ?string
    {
        return $this->intraHash;
    }

    /**
     * @param string|null $intraHash
     * @return ResourceLink
     */
    public function setIntraHash(?string $intraHash): ResourceLink
    {
        $this->intraHash = $intraHash;
        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return '';
    }

}
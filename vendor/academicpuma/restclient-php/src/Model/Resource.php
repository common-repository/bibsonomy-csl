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

use AcademicPuma\RestClient\Util\SimHashUtils;

/**
 * Description of Resource
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
abstract class Resource implements ModelObject
{

    /**
     * The inter user hash is less specific than the {@link #intraHash}.
     * @var string
     */
    protected $interHash;

    /**
     * The intra user hash is relatively strict and takes many fields of this
     * resource into account.
     * @var string
     */
    protected $intraHash;

    /**
     * Title of the Resource
     *
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $href;

    /**
     * @return string
     */
    public function getInterHash(): ?string
    {
        return $this->interHash;
    }

    /**
     * @param string|null $interHash
     * @return Resource
     */
    public function setInterHash(?string $interHash): Resource
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
     * @return Resource
     */
    public function setIntraHash(?string $intraHash): Resource
    {
        $this->intraHash = $intraHash;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return Resource
     */
    public function setTitle(?string $title): Resource
    {
        $this->title = $title;
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
     * @return Resource
     */
    public function setHref(?string $href): Resource
    {
        $this->href = $href;
        return $this;
    }

    public function __toString()
    {
        if (empty($this->intraHash)) {
            $this->setIntraHash(SimHashUtils::getSimHash2($this));
        }

        return $this->getIntraHash();
    }

    /**
     * @param Resource $b
     * @param string $sortKey
     *
     * @return integer
     */
    public abstract function compare(Resource $b, string $sortKey): int;

}

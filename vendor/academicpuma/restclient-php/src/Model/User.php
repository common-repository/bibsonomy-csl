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


/**
 * Description of User
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class User implements ModelObject, Comparable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $realname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $homepage;

    /**
     * @var Groups
     */
    private $groups;

    /**
     * @var bool
     */
    private $spammer;

    /**
     * @var int
     */
    private $prediction;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @var int
     */
    private $toClassify;

    /**
     * @var string
     */
    private $href;

    public function __construct()
    {
        $this->groups = new Groups();
    }


    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return User
     */
    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getRealname(): ?string
    {
        return $this->realname;
    }

    /**
     * @param string|null $realname
     * @return User
     */
    public function setRealname(?string $realname): User
    {
        $this->realname = $realname;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    /**
     * @param string|null $homepage
     * @return User
     */
    public function setHomepage(?string $homepage): User
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * @return Groups
     */
    public function getGroups(): ?Groups
    {
        return $this->groups;
    }

    /**
     * @param Groups|null $groups
     * @return User
     */
    public function setGroups(?Groups $groups): User
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSpammer(): bool
    {
        return $this->spammer;
    }

    /**
     * @param bool $spammer
     * @return User
     */
    public function setSpammer(bool $spammer): User
    {
        $this->spammer = $spammer;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrediction(): ?int
    {
        return $this->prediction;
    }

    /**
     * @param int|null $prediction
     * @return User
     */
    public function setPrediction(?int $prediction): User
    {
        $this->prediction = $prediction;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): ?string
    {
        return $this->algorithm;
    }

    /**
     * @param string|null $algorithm
     * @return User
     */
    public function setAlgorithm(?string $algorithm): User
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * @return int
     */
    public function getToClassify(): ?int
    {
        return $this->toClassify;
    }

    /**
     * @param int|null $toClassify
     * @return User
     */
    public function setToClassify(?int $toClassify): User
    {
        $this->toClassify = $toClassify;
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
     * @return User
     */
    public function setHref(?string $href): User
    {
        $this->href = $href;
        return $this;
    }

    public function compare(Comparable $b): int
    {
        return strcmp($this->getName(), $b->getName());
    }

    public function __toString()
    {
        return $this->name;
    }
}

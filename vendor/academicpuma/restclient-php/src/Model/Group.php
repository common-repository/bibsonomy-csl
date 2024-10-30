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
 * Description of Group
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class Group implements ModelObject, Comparable
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $realname;

    /**
     * @var string
     */
    private $homepage;

    /**
     * @var bool
     */
    private $organization;

    /**
     * @var Users
     */
    private $users;

    /**
     * @var Group
     */
    private $parent;

    /**
     * @var Groups
     */
    private $subgroups;

    /**
     * @var string
     */
    private $href;

    public function __construct()
    {
        $this->users = new Users();
        $this->subgroups = new Groups();
    }

    public function addUser(User $user): Group
    {
        $this->users->add($this->users->count(), $user);
        return $this;
    }

    public function addSubgroup(Group $group): Group
    {
        $this->subgroups->add($this->subgroups->count(), $group);
        return $this;
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
     * @return Group
     */
    public function setName(?string $name): Group
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Group
     */
    public function setDescription(?string $description): Group
    {
        $this->description = $description;
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
     * @return Group
     */
    public function setRealname(?string $realname): Group
    {
        $this->realname = $realname;
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
     * @return Group
     */
    public function setHomepage(?string $homepage): Group
    {
        $this->homepage = $homepage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrganization(): bool
    {
        return $this->organization;
    }

    /**
     * @param bool $organization
     * @return Group
     */
    public function setOrganization(bool $organization): Group
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return Users
     */
    public function getUsers(): ?Users
    {
        return $this->users;
    }

    /**
     * @param Users|null $users
     * @return Group
     */
    public function setUsers(?Users $users): Group
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return Group
     */
    public function getParent(): ?Group
    {
        return $this->parent;
    }

    /**
     * @param Group|null $parent
     * @return Group
     */
    public function setParent(?Group $parent): Group
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Groups
     */
    public function getSubgroups(): ?Groups
    {
        return $this->subgroups;
    }

    /**
     * @param Groups|null $subgroups
     * @return Group
     */
    public function setSubgroups(?Groups $subgroups): Group
    {
        $this->subgroups = $subgroups;
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
     * @return Group
     */
    public function setHref(?string $href): Group
    {
        $this->href = $href;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function compare(Comparable $b): int
    {
        return strcmp($this->getName(), $b->getName());
    }
}

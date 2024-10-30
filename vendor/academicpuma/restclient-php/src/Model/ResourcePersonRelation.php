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
 * Class ResourcePersonRelation
 * Represents a relation between a user and a resource/post.
 * @package AcademicPuma\RestClient\Model
 * @author kchoong
 */
class ResourcePersonRelation implements ModelObject
{
    /**
     * @var Person the person in the relation
     */
    private $person;

    /**
     * @var ResourceLink the resource in the relation
     */
    private $resourceLink;

    /**
     * @var string relation type between person and resource
     */
    private $relationType;

    /**
     * @var int appearance index of the person
     */
    private $personIndex;

    /**
     * @return Person
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return ResourcePersonRelation
     */
    public function setPerson(?Person $person): ResourcePersonRelation
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return ResourceLink
     */
    public function getResourceLink(): ?ResourceLink
    {
        return $this->resourceLink;
    }

    /**
     * @param ResourceLink|null $resourceLink
     * @return ResourcePersonRelation
     */
    public function setResourceLink(?ResourceLink $resourceLink): ResourcePersonRelation
    {
        $this->resourceLink = $resourceLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelationType(): ?string
    {
        return $this->relationType;
    }

    /**
     * @param string|null $relationType
     * @return ResourcePersonRelation
     */
    public function setRelationType(?string $relationType): ResourcePersonRelation
    {
        $this->relationType = $relationType;
        return $this;
    }

    /**
     * @return int
     */
    public function getPersonIndex(): ?int
    {
        return $this->personIndex;
    }

    /**
     * @param int|null $personIndex
     * @return ResourcePersonRelation
     */
    public function setPersonIndex(?int $personIndex): ResourcePersonRelation
    {
        $this->personIndex = $personIndex;
        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return '';
    }

}
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
 * Class PersonUtils
 * @package AcademicPuma\RestClient\Model
 * @author kchoong
 */
class Person implements ModelObject
{
    /**
     * @var string main name of the person
     */
    private $mainName;

    /**
     * @var string names of the person
     */
    private $names;

    /**
     * @var string linked username
     */
    private $user;

    /**
     * @var string person id
     */
    private $personId;

    /**
     * @var string gender
     */
    private $gender;

    /**
     * @var string homepage
     */
    private $homepage;

    /**
     * @var string e-mail
     */
    private $email;

    /**
     * @var string college
     */
    private $college;

    /**
     * @var string academic degree
     */
    private $academicDegree;

    /**
     * @var string ORCID
     */
    private $orcid;

    /**
     * @var string researcher id
     */
    private $researcherid;

    /**
     * @return string
     */
    public function getMainName(): ?string
    {
        return $this->mainName;
    }

    /**
     * @param string|null $mainName
     * @return Person
     */
    public function setMainName(?string $mainName): Person
    {
        $this->mainName = $mainName;
        return $this;
    }

    /**
     * @return string
     */
    public function getNames(): ?string
    {
        return $this->names;
    }

    /**
     * @param string|null $names
     * @return Person
     */
    public function setNames(?string $names): Person
    {
        $this->names = $names;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     * @return Person
     */
    public function setUser(?string $user): Person
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getPersonId(): ?string
    {
        return $this->personId;
    }

    /**
     * @param string|null $personId
     * @return Person
     */
    public function setPersonId(?string $personId): Person
    {
        $this->personId = $personId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     * @return Person
     */
    public function setGender(?string $gender): Person
    {
        $this->gender = $gender;
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
     * @return Person
     */
    public function setHomepage(?string $homepage): Person
    {
        $this->homepage = $homepage;
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
     * @return Person
     */
    public function setEmail(?string $email): Person
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollege(): ?string
    {
        return $this->college;
    }

    /**
     * @param string|null $college
     * @return Person
     */
    public function setCollege(?string $college): Person
    {
        $this->college = $college;
        return $this;
    }

    /**
     * @return string
     */
    public function getAcademicDegree(): ?string
    {
        return $this->academicDegree;
    }

    /**
     * @param string|null $academicDegree
     * @return Person
     */
    public function setAcademicDegree(?string $academicDegree): Person
    {
        $this->academicDegree = $academicDegree;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrcid(): ?string
    {
        return $this->orcid;
    }

    /**
     * @param string|null $orcid
     * @return Person
     */
    public function setOrcid(?string $orcid): Person
    {
        $this->orcid = $orcid;
        return $this;
    }

    /**
     * @return string
     */
    public function getResearcherid(): ?string
    {
        return $this->researcherid;
    }

    /**
     * @param string|null $researcherid
     * @return Person
     */
    public function setResearcherid(?string $researcherid): Person
    {
        $this->researcherid = $researcherid;
        return $this;
    }

    public function __toString()
    {
        return $this->mainName;
    }


}
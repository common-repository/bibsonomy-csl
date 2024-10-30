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
 * Class Project
 * @package AcademicPuma\RestClient\Model
 * @author kchoong
 */
class Project implements ModelObject
{
    /**
     * @var int id
     */
    private $id;

    /**
     * @var string external ID
     */
    private $externalId;

    /**
     * @var string internal ID
     */
    private $internalId;

    /**
     * @var string project title
     */
    private $title;

    /**
     * @var string sub title
     */
    private $subTitle;

    /**
     * @var string project description
     */
    private $description;

    /**
     * @var string project type
     */
    private $type;

    /**
     * @var string sponsor of the project
     */
    private $sponsor;

    /**
     * @var float project budget
     */
    private $budget;

    /**
     * @var string start date
     */
    private $startDate;

    /**
     * @var string end date
     */
    private $endDate;

    /**
     * @var Project parent project
     */
    private $parentProject;

    /**
     * @var Projects list of sub projects
     */
    private $subProjects;

    /**
     * @var array list of CRIS links
     */
    private $crisLinks;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Project
     */
    public function setId(?int $id): Project
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     * @return Project
     */
    public function setExternalId(?string $externalId): Project
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return string
     */
    public function getInternalId(): ?string
    {
        return $this->internalId;
    }

    /**
     * @param string|null $internalId
     * @return Project
     */
    public function setInternalId(?string $internalId): Project
    {
        $this->internalId = $internalId;
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
     * @return Project
     */
    public function setTitle(?string $title): Project
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    /**
     * @param string|null $subTitle
     * @return Project
     */
    public function setSubTitle(?string $subTitle): Project
    {
        $this->subTitle = $subTitle;
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
     * @return Project
     */
    public function setDescription(?string $description): Project
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Project
     */
    public function setType(?string $type): Project
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getSponsor(): ?string
    {
        return $this->sponsor;
    }

    /**
     * @param string|null $sponsor
     * @return Project
     */
    public function setSponsor(?string $sponsor): Project
    {
        $this->sponsor = $sponsor;
        return $this;
    }

    /**
     * @return float
     */
    public function getBudget(): ?float
    {
        return $this->budget;
    }

    /**
     * @param float|null $budget
     * @return Project
     */
    public function setBudget(?float $budget): Project
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @param string|null $startDate
     * @return Project
     */
    public function setStartDate(?string $startDate): Project
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * @param string|null $endDate
     * @return Project
     */
    public function setEndDate(?string $endDate): Project
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return Project
     */
    public function getParentProject(): ?Project
    {
        return $this->parentProject;
    }

    /**
     * @param Project|null $parentProject
     * @return Project
     */
    public function setParentProject(?Project $parentProject): Project
    {
        $this->parentProject = $parentProject;
        return $this;
    }

    /**
     * @return Projects
     */
    public function getSubProjects(): ?Projects
    {
        return $this->subProjects;
    }

    /**
     * @param Projects|null $subProjects
     * @return Project
     */
    public function setSubProjects(?Projects $subProjects): Project
    {
        $this->subProjects = $subProjects;
        return $this;
    }

    /**
     * @return array
     */
    public function getCrisLinks(): ?array
    {
        return $this->crisLinks;
    }

    /**
     * @param array|null $crisLinks
     * @return Project
     */
    public function setCrisLinks(?array $crisLinks): Project
    {
        $this->crisLinks = $crisLinks;
        return $this;
    }

    public function __toString()
    {
        return $this->externalId;
    }

}
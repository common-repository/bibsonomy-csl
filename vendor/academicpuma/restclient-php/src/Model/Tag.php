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
 * Represents a tag.
 *
 * @author Florian Fassing
 * @author Sebastian Böttger
 */
class Tag implements ModelObject
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var Tags
     */
    private $subTags;

    /**
     * @var Tags
     */
    private $superTags;

    /**
     * @var int
     */
    private $globalcount;

    /**
     * @var integer
     */
    private $usercount;

    /**
     * @var float
     */
    private $score;

    /**
     * @var float
     */
    private $confidence;

    /**
     * @var string
     */
    private $href;

    public function __construct()
    {
        $this->subTags = new Tags();
        $this->superTags = new Tags();
    }

    public function addSubTag(Tag $subTag): Tag
    {
        $this->subTags[] = $subTag;
        return $this;
    }

    public function addSuperTag(Tag $superTag): Tag
    {
        $this->superTags[] = $superTag;
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
     * @return Tag
     */
    public function setName(?string $name): Tag
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Tags
     */
    public function getSubTags(): ?Tags
    {
        return $this->subTags;
    }

    /**
     * @param Tags|null $subTags
     * @return Tag
     */
    public function setSubTags(?Tags $subTags): Tag
    {
        $this->subTags = $subTags;
        return $this;
    }

    /**
     * @return Tags
     */
    public function getSuperTags(): ?Tags
    {
        return $this->superTags;
    }

    /**
     * @param Tags|null $superTags
     * @return Tag
     */
    public function setSuperTags(?Tags $superTags): Tag
    {
        $this->superTags = $superTags;
        return $this;
    }

    /**
     * @return int
     */
    public function getGlobalcount(): ?int
    {
        return $this->globalcount;
    }

    /**
     * @param int|null $globalcount
     * @return Tag
     */
    public function setGlobalcount(?int $globalcount): Tag
    {
        $this->globalcount = $globalcount;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsercount(): ?int
    {
        return $this->usercount;
    }

    /**
     * @param int|null $usercount
     * @return Tag
     */
    public function setUsercount(?int $usercount): Tag
    {
        $this->usercount = $usercount;
        return $this;
    }

    /**
     * @return float
     */
    public function getScore(): ?float
    {
        return $this->score;
    }

    /**
     * @param float|null $score
     * @return Tag
     */
    public function setScore(?float $score): Tag
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @return float
     */
    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    /**
     * @param float|null $confidence
     * @return Tag
     */
    public function setConfidence(?float $confidence): Tag
    {
        $this->confidence = $confidence;
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
     * @return Tag
     */
    public function setHref(?string $href): Tag
    {
        $this->href = $href;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param Tag $b
     * @param string $sortingKey
     *
     * @return int
     */
    public function compare(Tag $b, string $sortingKey): int
    {
        switch ($sortingKey) {
            case 'name':
                return strcmp($this->getName(), $b->getName());
            case 'usercount':
                return ($this->usercount > $b->getUsercount()) ? 1 : -1;
            case 'globalcount':
                return ($this->globalcount > $b->getGlobalcount()) ? 1 : -1;
            default:
                return 0;
        }
    }
}

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
 * Description of Post
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class Post implements ModelObject
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var Tags
     */
    private $tag;

    /**
     * @var User
     */
    private $user;

    /**
     * TODO fix to be list of groups
     * @var Group
     */
    private $group;

    /**
     * @var Documents
     */
    private $documents;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $postingdate;

    /**
     * @var string
     */
    private $changedate;

    public function __construct()
    {
        $this->tag = new Tags();
    }

    public function addTag($tag): Post
    {
        $this->tag[] = $tag;
        return $this;
    }

    /**
     * @return Resource
     */
    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    /**
     * @param Resource $resource
     * @return Post
     */
    public function setResource($resource): Post
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return Tags
     */
    public function getTag(): ?Tags
    {
        return $this->tag;
    }

    /**
     * @param Tags|null $tag
     * @return Post
     */
    public function setTag(?Tags $tag): Post
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Post
     */
    public function setUser(?User $user): Post
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @param Group|null $group
     * @return Post
     */
    public function setGroup(?Group $group): Post
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Documents
     */
    public function getDocuments(): ?Documents
    {
        return $this->documents;
    }

    /**
     * @param Documents|null $documents
     * @return Post
     */
    public function setDocuments(?Documents $documents): Post
    {
        $this->documents = $documents;
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
     * @return Post
     */
    public function setDescription(?string $description): Post
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostingdate(): ?string
    {
        return $this->postingdate;
    }

    /**
     * @param string|null $postingdate
     * @return Post
     */
    public function setPostingdate(?string $postingdate): Post
    {
        $this->postingdate = $postingdate;
        return $this;
    }

    /**
     * @return string
     */
    public function getChangedate(): ?string
    {
        return $this->changedate;
    }

    /**
     * @param string|null $changedate
     * @return Post
     */
    public function setChangedate(?string $changedate): Post
    {
        $this->changedate = $changedate;
        return $this;
    }

    public function __toString()
    {
        return $this->resource . '/' . $this->user;
    }
}

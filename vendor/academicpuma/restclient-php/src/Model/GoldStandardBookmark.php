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


use AcademicPuma\RestClient\Util\Collection\ArrayList;

class GoldStandardBookmark extends Bookmark implements GoldStandard
{

    /**
     * @var ArrayList
     */
    private $references;

    /**
     * @var ArrayList
     */
    private $referencedBy;

    /**
     * @return ArrayList
     */
    public function getReferences(): ?ArrayList
    {
        return $this->references;
    }

    /**
     * @param ArrayList|null $references
     * @return GoldStandardBookmark
     */
    public function setReferences(?ArrayList $references): GoldStandardBookmark
    {
        $this->references = $references;
        return $this;
    }

    /**
     * @return ArrayList
     */
    public function getReferencedBy(): ?ArrayList
    {
        return $this->referencedBy;
    }

    /**
     * @param ArrayList|null $referencedBy
     * @return GoldStandardBookmark
     */
    public function setReferencedBy(?ArrayList $referencedBy): GoldStandardBookmark
    {
        $this->referencedBy = $referencedBy;
        return $this;
    }

}
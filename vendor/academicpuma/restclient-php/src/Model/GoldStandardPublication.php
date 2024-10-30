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

class GoldStandardPublication extends Bibtex implements GoldStandard
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
     * @var ArrayList
     */
    private $referencePartOfThisPublication;

    /**
     * @var ArrayList
     */
    private $referenceThisPublicationIsPublishedIn;

    /**
     * @return ArrayList
     */
    public function getReferences(): ?ArrayList
    {
        return $this->references;
    }

    /**
     * @param ArrayList|null $references
     * @return GoldStandardPublication
     */
    public function setReferences(?ArrayList $references): GoldStandardPublication
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
     * @return GoldStandardPublication
     */
    public function setReferencedBy(?ArrayList $referencedBy): GoldStandardPublication
    {
        $this->referencedBy = $referencedBy;
        return $this;
    }

    /**
     * @return ArrayList
     */
    public function getReferencePartOfThisPublication(): ?ArrayList
    {
        return $this->referencePartOfThisPublication;
    }

    /**
     * @param ArrayList|null $referencePartOfThisPublication
     * @return GoldStandardPublication
     */
    public function setReferencePartOfThisPublication(?ArrayList $referencePartOfThisPublication): GoldStandardPublication
    {
        $this->referencePartOfThisPublication = $referencePartOfThisPublication;
        return $this;
    }

    /**
     * @return ArrayList
     */
    public function getReferenceThisPublicationIsPublishedIn(): ?ArrayList
    {
        return $this->referenceThisPublicationIsPublishedIn;
    }

    /**
     * @param ArrayList|null $referenceThisPublicationIsPublishedIn
     * @return GoldStandardPublication
     */
    public function setReferenceThisPublicationIsPublishedIn(?ArrayList $referenceThisPublicationIsPublishedIn): GoldStandardPublication
    {
        $this->referenceThisPublicationIsPublishedIn = $referenceThisPublicationIsPublishedIn;
        return $this;
    }
}
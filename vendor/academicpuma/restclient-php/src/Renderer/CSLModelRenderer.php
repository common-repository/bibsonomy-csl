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

namespace AcademicPuma\RestClient\Renderer;

use AcademicPuma\RestClient\Config\Entrytype;
use AcademicPuma\RestClient\Model\Bibtex;
use AcademicPuma\RestClient\Model\Bookmark;
use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Model\Documents;
use AcademicPuma\RestClient\Model\Exceptions\InvalidModelObjectException;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Model\Resource;
use AcademicPuma\RestClient\Util\BibtexUtils;
use AcademicPuma\RestClient\Util\CSLUtils;
use stdClass;

/**
 * Converts model object structure into CSL JSON string.
 * @package AcademicPuma\RestClient\Renderer
 */
class CSLModelRenderer extends ModelRenderer
{

    private $cslDataObject;

    public function __construct()
    {
        $this->cslDataObject = [];
    }

    /**
     * @param Resource $resource
     * @return array
     * @throws InvalidModelObjectException
     */
    function serializeResource(Resource $resource): array
    {
        if ($resource instanceof Bookmark) {
            throw new InvalidModelObjectException("There is no CSL representation for resource type Bookmark");
        }
        return $this->serializeBibtex($resource);
    }

    /**
     * Serializes a <pre>$post</pre> recursively into its XML representation
     *
     * @param Post $post
     *
     * @return array
     * @throws InvalidModelObjectException
     */
    function serializePost(Post $post): array
    {
        $id = $post->getResource()->getIntraHash();
        if (!empty($post->getUser())) {
            $id .= $post->getUser()->getName();
        }
        $this->cslDataObject["id"] = $id;
        $documents = $post->getDocuments();
        if (!empty($documents)) {
            $this->cslDataObject["documents"] = $this->serializeDocuments($documents);
        }

        if (count($post->getTag()) > 0) {
            $this->cslDataObject["keyword"] = implode(" ", $post->getTag()->toArray());
        }

        return $this->serializeResource($post->getResource());
    }

    /**
     * @param Posts $posts
     * @return array
     * @throws InvalidModelObjectException
     */
    function serializePosts(Posts $posts): array
    {
        $cslPosts = [];

        foreach ($posts as $post) {
            $cslPosts[] = $this->serializePost($post);
        }

        return $cslPosts;
    }

    /**
     * @param Document $document
     *
     * @return stdClass
     * @internal param Post $post
     */
    function serializeDocument(Document $document): stdClass
    {
        $stdClass = new stdClass();
        $stdClass->fileHash = "";
        $stdClass->fileName = $document->getFilename();
        $stdClass->md5hash = $document->getMd5hash();
        $stdClass->temp = false;
        $matches = [];
        preg_match('/(http|https):\/\/[^\/]+\/api\/users\/([^\/]+).+/', $document->getHref(), $matches);
        $stdClass->userName = $matches[1];
        return $stdClass;
    }

    /**
     * Serializes a set of <pre>$documents</pre> into its XML representation
     *
     * @param Documents $documents
     * @return array
     */
    function serializeDocuments(Documents $documents): array
    {
        $ret = [];
        foreach ($documents as $document) {
            $ret[] = $this->serializeDocument($document);
        }
        return $ret;
    }

    /**
     * @param Bibtex $bibtex
     * @return array
     */
    private function serializeBibtex(Bibtex $bibtex): array
    {
        $this->mapAuthorsAndEditors($bibtex);

        $this->mapLocationAndAddress($bibtex);

        $this->mapBibtexKey($bibtex);

        $this->mapTitles($bibtex);

        $this->mapPublisher($bibtex);

        $this->mapPublicationTitleAndChapter($bibtex);

        $this->mapNumberAndIssue($bibtex);

        $this->mapAccessed($bibtex);

        $this->mapDate($bibtex);

        $this->mapPages($bibtex);

        if (!empty($bibtex->getVolume())) {
            $this->cslDataObject["volume"] = (BibtexUtils::cleanBibtex($bibtex->getVolume()));
        } else {
            $this->cslDataObject["volume"] = "";
        }
        if (!empty($bibtex->getUrl())) {
            $this->cslDataObject["URL"] = (BibtexUtils::cleanBibtex($bibtex->getUrl()));
        } else {
            $this->cslDataObject["URL"] = "";
        }
        if ($bibtex->hasMiscField('status')) {
            $this->cslDataObject["status"] = (BibtexUtils::cleanBibtex($bibtex->getMiscField("status")));
        }
        if ($bibtex->hasMiscField('isbn')) {
            $this->cslDataObject["ISBN"] = (BibtexUtils::cleanBibtex($bibtex->getMiscField("isbn")));
        } else {
            $this->cslDataObject["ISBN"] = "";
        }
        if ($bibtex->hasMiscField('issn')) {
            $this->cslDataObject["ISSN"] = (BibtexUtils::cleanBibtex($bibtex->getMiscField("issn")));
        } else {
            $this->cslDataObject["ISSN"] = '';
        }
        if ($bibtex->hasMiscField('revision')) {
            $this->cslDataObject["version"] = (BibtexUtils::cleanBibtex($bibtex->getMiscField("revision")));
        }
        if (!empty($bibtex->getAnnote()) ) {
            $this->cslDataObject["annote"] = (BibtexUtils::cleanBibtex($bibtex->getAnnote()));
        } else {
            $this->cslDataObject["annote"] = "";
        }
        if (!empty($bibtex->getEdition())) {
            $this->cslDataObject["edition"] = (BibtexUtils::cleanBibtex($bibtex->getEdition()));
        } else {
            $this->cslDataObject["edition"] = "";
        }
        if (!empty($bibtex->getBibtexAbstract())) {
            $this->cslDataObject["abstract"] = ($bibtex->getBibtexAbstract());
        } else {
            $this->cslDataObject["abstract"] = "";
        }
        if ($bibtex->hasMiscField('doi')) {
            $this->cslDataObject["DOI"] = (BibtexUtils::cleanBibtex($bibtex->getMiscField("doi")));
        } else {
            $this->cslDataObject["DOI"] = "";
        }
        if (!empty($bibtex->getNote())) {
            $this->cslDataObject["note"] = (BibtexUtils::cleanBibtex($bibtex->getNote()));
        } else {
            $this->cslDataObject["note"] = "";
        }

        if (Entrytype::INPROCEEDINGS === $bibtex->getEntrytype()) {
            $this->cslDataObject["collection-editor"] = [];
            $this->cslDataObject["container-author"] = [];
            $this->cslDataObject["event-date"] = $this->cslDataObject["issued"];
            $this->cslDataObject["page-first"] = "";
            $this->cslDataObject["status"] = "";
            $this->cslDataObject["version"] = "";
        }

        $this->cslDataObject["type"] = CSLUtils::getEntrytype($bibtex->getEntrytype());

        return $this->cslDataObject;
    }


    /**
     * @param Bibtex $bibtex
     */
    private function mapTitles(Bibtex $bibtex)
    {
        // mapping journal, booktitle and series
        $cleanedJournal = BibtexUtils::cleanBibtex($bibtex->getJournal());
        $cleanedBooktitle = BibtexUtils::cleanBibtex($bibtex->getBooktitle());
        $cleanedSeries = BibtexUtils::cleanBibtex($bibtex->getSeries());

        if (!empty($cleanedJournal)) {
            $containerTitleToUse = $cleanedJournal;
        } else {
            if (!empty($cleanedBooktitle)) {
                $containerTitleToUse = $cleanedBooktitle;
            } else {
                $containerTitleToUse = $cleanedSeries;
            }
        }
        if (!empty($containerTitleToUse)) {
            $this->cslDataObject["container-title"] = $containerTitleToUse;
            $this->cslDataObject["collection-title"] = $cleanedSeries;
        } else {
            $this->cslDataObject["container-title"] = "";
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapPublisher(Bibtex $bibtex)
    {
        // mapping publisher, techreport, thesis, organization
        $publisher = $bibtex->getPublisher();
        $school = BibtexUtils::cleanBibtex($bibtex->getSchool());
        $institution = BibtexUtils::cleanBibtex($bibtex->getInstitution());
        $organisation = BibtexUtils::cleanBibtex($bibtex->getOrganization());

        if (!empty($publisher)) {
            $this->cslDataObject["publisher"] = $publisher;

        } else {
            if (Entrytype::TECHREPORT === $bibtex->getEntrytype() && !empty($institution)) {
                $this->cslDataObject["publisher"] = $institution;

            } else {
                if (Entrytype::PHDTHESIS === $bibtex->getEntrytype() && !empty($school)) {
                    $this->cslDataObject["publisher"] = $school;
                    $this->cslDataObject["genre"] = "PhD dissertation";

                } else {
                    if (Entrytype::MASTERTHESIS === $bibtex->getEntrytype() && !empty($school)) {
                        $this->cslDataObject["publisher"] = $school;
                        $this->cslDataObject["genre"] = "Master thesis";

                    } else {

                        if (!empty($organisation)) {
                            $this->cslDataObject["publisher"] = $organisation;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapNumberAndIssue(Bibtex $bibtex)
    {
        $cleanedNumber = BibtexUtils::cleanBibtex($bibtex->getNumber());
        $cleanedIssue = BibtexUtils::cleanBibtex($bibtex->getMiscField("issue"));

        if (!empty($cleanedNumber)) {
            $this->cslDataObject["number"] = $cleanedNumber;
        } else {
            $this->cslDataObject["number"] = "";
        }

        if (!empty($cleanedIssue)) {
            $issueToUse = $cleanedIssue;
        } else {
            if (!empty($cleanedNumber)) {
                $issueToUse = $cleanedNumber;
            }
        }
        if (!empty($issueToUse)) {
            $this->cslDataObject["issue"] = $issueToUse;
        } else {
            $this->cslDataObject["issue"] = "";
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapDate(Bibtex $bibtex)
    {

        // date mapping
        $urlDate = BibtexUtils::cleanBibtex($bibtex->getMiscField("urldate"));
        $cleanedDate = BibtexUtils::cleanBibtex($bibtex->getMiscField("date"));
        $date = [];
        if (Entrytype::ELECTRONIC === $bibtex->getEntrytype() && !empty($urlDate)) {
            $date["raw"] = $urlDate;
        } else {
            if (!empty($cleanedDate)) {
                $date["raw"] = $cleanedDate;
                $date["event-date"] = $date;
            } else {
                $year = $bibtex->getYear();
                $date["literal"] = $year;
                if (is_numeric($year)) {
                    $date["date-parts"] = [[$year]];
                    $cleanedMonth = CSLUtils::convertMonth(BibtexUtils::cleanBibtex($bibtex->getMonth()));
                    $cleanedDay = "" . BibtexUtils::cleanBibtex($bibtex->getDay());
                    if (!empty($cleanedMonth)) {
                        $date["date-parts"][0][] = $cleanedMonth;
                    }

                    if (!empty($cleanedDay)) {
                        // add "00" to date-parts array, if month is missing but day is provided
                        if (empty($cleanedMonth)) {
                            $date["date-parts"][0][] = "00";
                        }
                        $date["date-parts"][0][] = $cleanedDay;
                    }
                } else {
                    $date["raw"] = $year;
                    $date["date-parts"] = [];
                }
            }
        }

        $this->cslDataObject["issued"] = $date;
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapPages(Bibtex $bibtex)
    {

        $cleanedPages = BibtexUtils::cleanBibtex($bibtex->getPages());

        if (!empty($cleanedPages)) {
            // replace possible page dashes with simple dash
            $pageDashes = array("--", "---");
            $cleanedPages = str_replace($pageDashes, "-", $cleanedPages);
            $this->cslDataObject["page"] = $cleanedPages;

        } else {
            $this->cslDataObject["page"] = "";
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapAccessed(Bibtex $bibtex)
    {
        $accessed = BibtexUtils::cleanBibtex($bibtex->getMiscField("accessed"));
        if (!empty($accessed)) {
            $accessedDate = [];
            $accessedDate["literal"] = $accessed;
            $this->cslDataObject["accessed"] = $accessedDate;
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapPublicationTitleAndChapter(Bibtex $bibtex)
    {
        // mapping chapter
        $chapter = $bibtex->getChapter();
        if (!empty($chapter)) {
            $this->cslDataObject["chapter-number"] = BibtexUtils::cleanBibtex($chapter);
        }

        // mapping title
        $title = BibtexUtils::cleanBibtex($bibtex->getTitle());
        if (!empty($title)) {
            $this->cslDataObject["title"] = $title;
        } else {
            // XXX: title is a required field
            $this->cslDataObject["title"] = $chapter;
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapBibtexKey(Bibtex $bibtex)
    {
        // mapping bibtexkey
        $bibtexKey = BibtexUtils::cleanBibtex($bibtex->getBibtexKey());
        if (!empty($bibtexKey)) {
            $this->cslDataObject["citation-label"] = BibtexUtils::cleanBibtex($bibtex->getBibtexKey());
        } else {
            $this->cslDataObject["citation-label"] = "";
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapLocationAndAddress(Bibtex $bibtex)
    {
        // mapping address

        $cleanedLocation = BibtexUtils::cleanBibtex($bibtex->getMiscField("location"));
        $cleanedAddress = BibtexUtils::cleanBibtex($bibtex->getAddress());
        if (!empty($cleanedLocation) && $bibtex->hasMiscField("location")) {
            $this->cslDataObject["event-place"] = $cleanedLocation;
            $this->cslDataObject["publisher-place"] = $cleanedLocation;
        } else {
            if (!empty($cleanedAddress)) {
                $this->cslDataObject["event-place"] = $cleanedAddress;
                $this->cslDataObject["publisher-place"] = $cleanedAddress;
            } else {
                $this->cslDataObject["event-place"] = "";
                $this->cslDataObject["publisher-place"] = "";
            }
        }
    }

    /**
     * @param Bibtex $bibtex
     */
    private function mapAuthorsAndEditors(Bibtex $bibtex)
    {
        $authors = [];
        $editors = [];

        $ed = $bibtex->getEditor();
        $at = $bibtex->getAuthor();

        if (!empty($ed)) {
            $editors = CSLUtils::getCSLNames($bibtex->getEditor());
        }
        if (!empty($at)) {
            $authors = CSLUtils::getCSLNames($bibtex->getAuthor());
        }

        $this->cslDataObject["author"] = $authors;
        $this->cslDataObject["editor"] = $editors;
    }
}
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

use AcademicPuma\RestClient\Util\BibtexUtils;
use AcademicPuma\RestClient\Util\SortingUtils;
use AcademicPuma\RestClient\Util\StringUtils;

/**
 * Description of Publication
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class Bibtex extends Resource
{
    /**
     * BibTeX Key is used to cite or cross-reference the publication.
     * @var string
     */
    private $bibtexKey;

    /**
     * @var string
     */
    private $key;

    /**
     * Misc fields can be used to add additional fields which are not defined by BibTeX
     * @var string
     */
    private $misc;

    /**
     * Abstract of the publication
     * @var string
     */
    private $bibtexAbstract;

    /**
     * A BibTeX can contain a different types of entries
     * @var string
     */
    private $entrytype;

    /**
     * Publisher's address (usually just the city, but can be the full address for lesser-known publishers)
     * @var string
     */
    private $address;

    /**
     * An annotation for annotated bibliography styles (not typical)
     * @var string
     */
    private $annote;

    /**
     * The name(s) of the author(s) (in the case of more than one author, separated by and
     * @var string
     */
    private $author;

    /**
     * The title of the book, if only part of it is being cited
     * @var string
     */
    private $booktitle;

    /**
     * The chapter number
     * @var string
     */
    private $chapter;

    /**
     * The key of the cross-referenced entry
     * @var string
     */
    private $crossref;

    /**
     * The edition of a book, long form (such as "First" or "Second")
     * @var string
     */
    private $edition;

    /**
     * The name(s) of the editor(s)
     * @var string
     */
    private $editor;

    /**
     * How it was published, if the publishing method is nonstandard
     * @var string
     */
    private $howpublished;

    /**
     * The institution that was involved in the publishing, but not necessarily the publisher
     * @var string
     */
    private $institution;

    /**
     * The conference sponsor
     * @var string
     */
    private $organization;

    /**
     * The journal or magazine the work was published in
     * @var string
     */
    private $journal;

    /**
     * Miscellaneous extra information
     * @var string
     */
    private $note;

    /**
     * The "(issue) number" of a journal, magazine, or tech-report, if applicable. (Most publications have a "volume",
     * but no "number" field.)
     * @var string
     */
    private $number;

    /**
     * Page numbers, separated either by commas or double-hyphens
     * @var string
     */
    private $pages;

    /**
     * The publisher's name
     * @var string
     */
    private $publisher;

    /**
     * The school where the thesis was written
     * @var string
     */
    private $school;

    /**
     * The series of books the book was published in (e.g. "The Hardy Boys" or "Lecture Notes in Computer Science")
     * @var string
     */
    private $series;

    /**
     * The volume of a journal or multi-volume book
     * @var string
     */
    private $volume;

    /**
     * The day of publication (or, if unpublished, the day of creation)
     * @var string
     */
    private $day;

    /**
     * The month of publication (or, if unpublished, the month of creation)
     * @var string
     */
    private $month;

    /**
     * The year of publication (or, if unpublished, the year of creation)
     * @var string
     */
    private $year;

    /**
     * The field overriding the default type of publication (e.g. "Research Note" for techreport, "{PhD} dissertation"
     * for phdthesis, "Section" for inbook/incollection)
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $url;

    /**
     * Private note of the publication
     * @var string
     */
    private $privnote;

    /**
     * @var array
     */
    private $miscFields = [];

    /**
     * @var bool
     */
    private $miscFieldsParsed = false;

    /**
     * @var array
     */
    private $keywords;

    /**
     * Get conference names of inproceedings.
     * Conference names are normally stored in the booktitle field of
     * its BibTeX. Another norm are abbreviated forms of the conference name in curly braces.
     * @return string
     */
    public function getConference(): string
    {
        $booktitle = $this->booktitle;
        if (strpos($booktitle, '{') >= 0 && strpos($booktitle, '}') > 0) {
            return substr($booktitle, strpos($booktitle, '{') + 1, strpos($booktitle, '}') - strpos($booktitle, '{') - 1);
        } else if (strpos($booktitle, '(') >= 0 && strpos($booktitle, ')') > 0) {
            return substr($booktitle, strpos($booktitle, '(') + 1, strpos($booktitle, ')') - strpos($booktitle, '(') - 1);
        } else {
            return $booktitle;
        }
    }

    /**
     * @return string
     */
    public function getBibtexKey(): ?string
    {
        return $this->bibtexKey;
    }

    /**
     * @param string|null $bibtexKey
     * @return Bibtex
     */
    public function setBibtexKey(?string $bibtexKey): Bibtex
    {
        $this->bibtexKey = $bibtexKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string|null $key
     * @return Bibtex
     */
    public function setKey(?string $key): Bibtex
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getMisc(): ?string
    {
        return $this->misc;
    }

    /**
     * @param string|null $misc
     * @return Bibtex
     */
    public function setMisc(?string $misc): Bibtex
    {
        $this->misc = $misc;
        return $this;
    }

    /**
     * @return string
     */
    public function getBibtexAbstract(): ?string
    {
        return $this->bibtexAbstract;
    }

    /**
     * @param string|null $bibtexAbstract
     * @return Bibtex
     */
    public function setBibtexAbstract(?string $bibtexAbstract): Bibtex
    {
        $this->bibtexAbstract = $bibtexAbstract;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntrytype(): ?string
    {
        return $this->entrytype;
    }

    /**
     * @param string|null $entrytype
     * @return Bibtex
     */
    public function setEntrytype(?string $entrytype): Bibtex
    {
        $this->entrytype = $entrytype;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return Bibtex
     */
    public function setAddress(?string $address): Bibtex
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnnote(): ?string
    {
        return $this->annote;
    }

    /**
     * @param string|null $annote
     * @return Bibtex
     */
    public function setAnnote(?string $annote): Bibtex
    {
        $this->annote = $annote;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     * @return Bibtex
     */
    public function setAuthor(?string $author): Bibtex
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getBooktitle(): ?string
    {
        return $this->booktitle;
    }

    /**
     * @param string|null $booktitle
     * @return Bibtex
     */
    public function setBooktitle(?string $booktitle): Bibtex
    {
        $this->booktitle = $booktitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getChapter(): ?string
    {
        return $this->chapter;
    }

    /**
     * @param string|null $chapter
     * @return Bibtex
     */
    public function setChapter(?string $chapter): Bibtex
    {
        $this->chapter = $chapter;
        return $this;
    }

    /**
     * @return string
     */
    public function getCrossref(): ?string
    {
        return $this->crossref;
    }

    /**
     * @param string|null $crossref
     * @return Bibtex
     */
    public function setCrossref(?string $crossref): Bibtex
    {
        $this->crossref = $crossref;
        return $this;
    }

    /**
     * @return string
     */
    public function getEdition(): ?string
    {
        return $this->edition;
    }

    /**
     * @param string|null $edition
     * @return Bibtex
     */
    public function setEdition(?string $edition): Bibtex
    {
        $this->edition = $edition;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditor(): ?string
    {
        return $this->editor;
    }

    /**
     * @param string|null $editor
     * @return Bibtex
     */
    public function setEditor(?string $editor): Bibtex
    {
        $this->editor = $editor;
        return $this;
    }

    /**
     * @return string
     */
    public function getHowpublished(): ?string
    {
        return $this->howpublished;
    }

    /**
     * @param string|null $howpublished
     * @return Bibtex
     */
    public function setHowpublished(?string $howpublished): Bibtex
    {
        $this->howpublished = $howpublished;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    /**
     * @param string|null $institution
     * @return Bibtex
     */
    public function setInstitution(?string $institution): Bibtex
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    /**
     * @param string|null $organization
     * @return Bibtex
     */
    public function setOrganization(?string $organization): Bibtex
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return string
     */
    public function getJournal(): ?string
    {
        return $this->journal;
    }

    /**
     * @param string|null $journal
     * @return Bibtex
     */
    public function setJournal(?string $journal): Bibtex
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     * @return Bibtex
     */
    public function setNote(?string $note): Bibtex
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string|null $number
     * @return Bibtex
     */
    public function setNumber(?string $number): Bibtex
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getPages(): ?string
    {
        return $this->pages;
    }

    /**
     * @param string|null $pages
     * @return Bibtex
     */
    public function setPages(?string $pages): Bibtex
    {
        $this->pages = $pages;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * @param string|null $publisher
     * @return Bibtex
     */
    public function setPublisher(?string $publisher): Bibtex
    {
        $this->publisher = $publisher;
        return $this;
    }

    /**
     * @return string
     */
    public function getSchool(): ?string
    {
        return $this->school;
    }

    /**
     * @param string|null $school
     * @return Bibtex
     */
    public function setSchool(?string $school): Bibtex
    {
        $this->school = $school;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeries(): ?string
    {
        return $this->series;
    }

    /**
     * @param string|null $series
     * @return Bibtex
     */
    public function setSeries(?string $series): Bibtex
    {
        $this->series = $series;
        return $this;
    }

    /**
     * @return string
     */
    public function getVolume(): ?string
    {
        return $this->volume;
    }

    /**
     * @param string|null $volume
     * @return Bibtex
     */
    public function setVolume(?string $volume): Bibtex
    {
        $this->volume = $volume;
        return $this;
    }

    /**
     * @return string
     */
    public function getDay(): ?string
    {
        return $this->day;
    }

    /**
     * @param string|null $day
     * @return Bibtex
     */
    public function setDay(?string $day): Bibtex
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return string
     */
    public function getMonth(): ?string
    {
        return $this->month;
    }

    /**
     * @param string|null $month
     * @return Bibtex
     */
    public function setMonth(?string $month): Bibtex
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return string
     */
    public function getYear(): ?string
    {
        return $this->year;
    }

    /**
     * @param string|null $year
     * @return Bibtex
     */
    public function setYear(?string $year): Bibtex
    {
        $this->year = $year;
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
     * @return Bibtex
     */
    public function setType(?string $type): Bibtex
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return Bibtex
     */
    public function setUrl(?string $url): Bibtex
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivnote(): ?string
    {
        return $this->privnote;
    }

    /**
     * @param string|null $privnote
     * @return Bibtex
     */
    public function setPrivnote(?string $privnote): Bibtex
    {
        $this->privnote = $privnote;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiscFields(): ?array
    {
        return $this->miscFields;
    }

    /**
     * @param array|null $miscFields
     * @return Bibtex
     */
    public function setMiscFields(?array $miscFields): Bibtex
    {
        $this->miscFields = $miscFields;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMiscFieldsParsed(): bool
    {
        return $this->miscFieldsParsed;
    }

    /**
     * @param bool $miscFieldsParsed
     * @return Bibtex
     */
    public function setMiscFieldsParsed(bool $miscFieldsParsed): Bibtex
    {
        $this->miscFieldsParsed = $miscFieldsParsed;
        return $this;
    }

    /**
     * @return array
     */
    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    /**
     * @param array|null $keywords
     * @return Bibtex
     */
    public function setKeywords(?array $keywords): Bibtex
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function hasMiscField(string $field): bool
    {
        if (!$this->miscFieldsParsed) {
            $this->miscFields = BibtexUtils::parseMiscFieldString($this->getMisc());
            $this->miscFieldsParsed = true;
        }
        return array_key_exists($field, $this->miscFields) && !empty($this->miscFields[$field]);
    }

    /**
     * Returns the value of a specified field you want to get from misc. Returns nulls, if the misc field does
     * not exist.
     *
     * @param string $field which field you want to get
     *
     * @return null|string
     */
    public function getMiscField(string $field): ?string
    {
        if (!$this->miscFieldsParsed) {
            $this->miscFields = BibtexUtils::parseMiscFieldString($this->getMisc());
            $this->miscFieldsParsed = true;
        }

        if (array_key_exists($field, $this->miscFields)) {
            return $this->miscFields[$field];
        }

        return null;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return 'bibtex/' . parent::__toString();
    }

    /**
     * strcasecmp method returns < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
     * E.g. strcmp('a', 'B') returns -1
     *
     * @param Bibtex $b
     * @param string $sortKey
     *
     * @return int
     */
    public function compare($b, string $sortKey): int
    {
        switch ($sortKey) {
            case 'author':
                $aAuthor = StringUtils::isNullOrEmptyString($this->getAuthor()) ? $this->getEditor() : $this->getAuthor();
                $bAuthor = StringUtils::isNullOrEmptyString($b->getAuthor()) ? $b->getEditor() : $b->getAuthor();
                return strcasecmp(StringUtils::transliterateString($aAuthor), StringUtils::transliterateString($bAuthor));
            case 'title':
                return strcasecmp($this->getTitle(), $b->getTitle());
            case 'entrytype':
                return strcasecmp($this->getEntrytype(), $b->getEntrytype());
            case 'year':
                return $this->getYear() > $b->getYear() ? 1 : -1;
            case 'month':
                return SortingUtils::compareMonth($this, $b);
            default:
                return 0;
        }
    }

}

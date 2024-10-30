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

namespace AcademicPuma\RestClient\Util;

use AcademicPuma\RestClient\Model;

/**
 *
 *
 *
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class BibtexModelUtils
{


    const ADDITIONAL_MISC_FIELD_DESCRIPTION = 'description';
    const ADDITIONAL_MISC_FIELD_KEYWORDS = 'keywords';
    const ADDITIONAL_MISC_FIELD_BIBURL = 'biburl';
    const ADDITIONAL_MISC_FIELD_PRIVNOTE = 'privnote';
    const ADDITIONAL_MISC_FIELD_ADDED_AT = 'added-at';
    const ADDITIONAL_MISC_FIELD_TIMESTAMP = 'timestamp';

    const ASSIGNMENT_OPERATOR = '=';
    const DEFAULT_INTENDATION = "  ";
    const KEYVALUE_SEPARATOR = ',';
    const DEFAULT_OPENING_BRACKET = '{';
    const DEFAULT_CLOSING_BRACKET = '}';

    public static $STD_ATTRIBUTES = ['bibtexKey', 'title', 'year', 'author', 'editor', 'entrytype'];

    public static $FULL_BIBTEX_ATTRIBUTES = [
        'bibtexAbstract', 'address', 'annote', 'booktitle', 'chapter', 'crossref', 'edition',
        'howpublished', 'institution', 'organization', 'journal', 'note',
        'number', 'pages', 'publisher', 'school', 'series', 'volume',
        'month', 'year', 'url', 'privnote'];

    public static $ATTRIBUTE_VALUE_LENGTH_LIMITS = [
        'volume' => 255,
        'chapter' => 255,
        'edition' => 255,
        'month' => 45,
        'day' => 45,
        'howPublished' => 255,
        'institution' => 255,
        'organization' => 255,
        'publisher' => 255,
        'address' => 255,
        'school' => 255,
        'series' => 255,
        'bibtexKey' => 255,
        'pages' => 50,
        'number' => 45,
        'crossref' => 255,
        'entrytype' => 30,
        'year' => 45
    ];


    /**
     * If no values present, this function sets default values.
     * Considered fields: author, editor, year, entrytype, title, bibtexkey
     *
     * @param \AcademicPuma\RestClient\Model\Bibtex $bibtex
     *
     */
    public static function appendRequiredFields(Model\Bibtex &$bibtex)
    {

        if (($bibtex->getAuthor() === null || $bibtex->getAuthor() === '') && ($bibtex->getEditor() === null || $bibtex->getEditor() === '')) {
            $bibtex->setAuthor(trim('noauthor'));
        }
        if ($bibtex->getYear() === null || $bibtex->getYear() === '') {
            $bibtex->setYear('nodate');
        }
        if ($bibtex->getEntrytype() === null || $bibtex->getEntrytype() === '') {
            $bibtex->setEntrytype('misc');
        }
        if ($bibtex->getTitle() === null || $bibtex->getTitle() === '') {
            $bibtex->setTitle('notitle');
        }
        self::appendBibtexKey($bibtex);
    }

    /**
     * appends an
     *
     * @param \AcademicPuma\RestClient\Model\Bibtex $bibtex
     *
     */
    public static function appendBibtexKey(Model\Bibtex &$bibtex)
    {

        $aut = preg_split("/" . SimHashUtils::PERSON_NAME_DELIMITER . "/", $bibtex->getAuthor());
        $edt = preg_split("/" . SimHashUtils::PERSON_NAME_DELIMITER . "/", $bibtex->getEditor());


        $authors = self::persons($aut);
        $editors = self::persons($edt);

        $bibtexKey = self::generateBibtexKey($authors, $editors, $bibtex->getYear(), $bibtex->getTitle());
        $bibtex->setBibtexKey($bibtexKey);
    }

    /**
     *
     * @param array|Collection\ArrayList $authors
     * @param array|Collection\ArrayList $editors
     * @param string $year
     * @param string $title
     * @return string
     */
    public static function generateBibtexKey($authors, $editors, $year, $title)
    {

        /* get author */
        $first = SimHashUtils::getFirstPersonsLastName($authors);
        if ($first == null) {
            $first = SimHashUtils::getFirstPersonsLastName($editors);
            if ($first == null) {
                $first = "noauthororeditor";
            }
        }
        $ret = mb_strtolower($first);

        /* the year */
        if ((is_numeric($year) && $year != 0) || !empty($year)) {
            $ret .= trim($year);
        }

        /* first relevant word of the title */
        if (!empty($title)) {
            /* best guess: pick first word with more than 4 characters, longest first word */

            $ret .= mb_strtolower(self::getFirstRelevantWord($title));
        }

        return preg_replace("![^a-z0-9]!", "", StringUtils::transliterateString($ret));
    }


    /**
     * @param $string
     *
     * @return string
     */
    public static function getFirstRelevantWord($string)
    {
        $split = preg_split('!\s!', $string);
        foreach ($split as $s) {
            /**
             * @var string $ss first word of the string
             */
            $ss = preg_replace("[^a-zA-Z0-9]", "", $s);
            if (strlen($ss) > 4) {
                return $ss;
            }
        }
        return "";
    }


    public static function limitValueLength(Model\Bibtex &$bibtex)
    {

        foreach (self::$ATTRIBUTE_VALUE_LENGTH_LIMITS as $prop => $length) {
            $reflClass = new \ReflectionClass($bibtex);
            $getMethod = $reflClass->getMethod('get' . ucfirst($prop));
            $setMethod = $reflClass->getMethod('set' . ucfirst($prop));
            $value = $getMethod->invoke($bibtex);

            if (strlen($value) > $length) {
                $setMethod->invoke($bibtex, substr($value, 0, $length));
            }
        }
    }

    /**
     * @param array|Collection\ArrayList $person
     *
     * @return Collection\ArrayList
     */
    public static function persons($person)
    {
        $p = [];
        if (!empty($person)) {
            $empty = true;
            foreach ($person as $ed) {
                $empty = empty($ed) && $empty;
            }
            if (!$empty) {
                $p = Person::createPersonsListFromArray($person);
                return $p;
            }
        }
        return $p;
    }

    public static function parseMiscFieldString($miscFieldString)
    {
        return StringUtils::parseBracketedKeyValuePairs($miscFieldString, self::ASSIGNMENT_OPERATOR, self::KEYVALUE_SEPARATOR, self::DEFAULT_OPENING_BRACKET, self::DEFAULT_CLOSING_BRACKET);
    }

    /**
     * Creates a misc property in BibTeX style. if $bibtex contains any misc properties,
     * it returns that string with appended new misc property.
     *
     * @param \AcademicPuma\RestClient\Model\Bibtex $bibtex
     * @param string $prop
     * @param string $value (optional)
     *
     * @return string formatted for BibTeX misc property
     */
    public static function appendMiscProp(Model\Bibtex &$bibtex, $prop, $value = null)
    {
        try {
            if ($value == null) {
                $reflClass = new \ReflectionClass($bibtex);
                $method = $reflClass->getMethod('get' . ucfirst($prop));
                $value = $method->invoke($bibtex);
            }
        } catch (\ReflectionException $e) {
            return null;
        }

        if ($bibtex->getMisc() !== null && $bibtex->getMisc() !== '') {
            $misc = $bibtex->getMisc() . ",\n" . $prop . " = {" . $value . "}";
        } else {
            $misc = $prop . " = {" . $value . "}";
        }

        return $misc;
    }

    public static function cleanBibtex($val)
    {
        //TODO: Implement method
        return $val;
    }
}

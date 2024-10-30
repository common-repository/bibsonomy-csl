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
 * SimHashUtils contains a set of static methods to serialize and normalize
 * person names and titles. The function getSimHash1 calculates the interhash of
 * a Rousource.
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class SimHashUtils
{

    /**
     * @var string
     */
    const SINGLE_LETTER = '/(\p{L})/';

    /**
     * By default, all author and editor names are in "Last, First" order
     *
     * @var bool
     */
    const DEFAULT_LAST_FIRST_NAMES = true;

    /**
     * the delimiter used for separating person names
     *
     * @var string
     */
    const PERSON_NAME_DELIMITER = " and ";


    /**
     * the delimiter used for separating first and last name
     */
    const FIRSTNAME_LASTNAME_DELIMITER = ", ";

    /**
     *
     *
     * @param \AcademicPuma\RestClient\Model\Bibtex $publication object
     *
     * @return string
     */
    private static function getBibtexSimHash1(Model\Bibtex $publication)
    {
        $authors = PersonUtils::createPersonsListFromArray(explode(self::PERSON_NAME_DELIMITER, $publication->getAuthor()));
        //$serializedAuthors = PersonUtils::serializePersonNames($authors);
        $normalizedAuthors = self::getNormalizedPersons($authors);

        if (empty($normalizedAuthors)) {

            // no author set --> take editor
            $editors = PersonUtils::createPersonsListFromArray(explode(self::PERSON_NAME_DELIMITER, $publication->getEditor()));

            return StringUtils::md5utf8(
                self::getNormalizedTitle($publication->getTitle()) . " " .
                self::getNormalizedPersons($editors) . " " .
                self::getNormalizedYear($publication->getYear())
            );
        }
        // author set
        return StringUtils::md5utf8(
            self::getNormalizedTitle($publication->getTitle()) . " " .
            self::getNormalizedPersons($authors) . " " .
            self::getNormalizedYear($publication->getYear())
        );
    }

    /**
     * Intrahash
     *
     * @param \AcademicPuma\RestClient\Model\Bibtex $bibtex
     *
     * @return string
     */
    private static function getBibtexSimHash2(Model\Bibtex $bibtex)
    {

        $authors = !empty($bibtex->getAuthor()) ? PersonUtils::createPersonsListFromArray(explode(self::PERSON_NAME_DELIMITER, $bibtex->getAuthor())) : new Collection\ArrayList();
        $editors = !empty($bibtex->getEditor()) ? PersonUtils::createPersonsListFromArray(explode(self::PERSON_NAME_DELIMITER, $bibtex->getEditor())) : new Collection\ArrayList();

        $str = StringUtils::removeNonNumbersOrLettersOrDotsOrSpace($bibtex->getTitle()) . " " .
            StringUtils::removeNonNumbersOrLettersOrDotsOrSpace(PersonUtils::serializePersonNames($authors, false)) . " " .
            StringUtils::removeNonNumbersOrLettersOrDotsOrSpace(PersonUtils::serializePersonNames($editors, false)) . " " .
            StringUtils::removeNonNumbersOrLettersOrDotsOrSpace($bibtex->getYear()) . " " .
            StringUtils::removeNonNumbersOrLettersOrDotsOrSpace($bibtex->getEntrytype()) . " " .
            StringUtils::removeNonNumbersOrLettersOrDotsOrSpace($bibtex->getJournal()) . " " .
            StringUtils::removeNonNumbersOrLettersOrDotsOrSpace($bibtex->getBooktitle()) . " " .
            StringUtils::removeNonNumbersOrLetters($bibtex->getVolume()) . " " .
            StringUtils::removeNonNumbersOrLetters($bibtex->getNumber());

        return StringUtils::md5utf8($str);
    }

    private static function getBookmarkSimHash1(Model\Bookmark $bookmark)
    {
        return StringUtils::md5utf8($bookmark->getUrl());
    }

    private static function getBookmarkSimHash2(Model\Bookmark $bookmark)
    {
        return StringUtils::md5utf8($bookmark->getUrl());
    }


    public static function getSimHash1(Model\Resource $resource)
    {
        if ($resource instanceof Model\Bibtex) {
            return self::getBibtexSimHash1($resource);
        } else if ($resource instanceof Model\Bookmark) {
            return self::getBookmarkSimHash1($resource);
        }
        return null;
    }

    public static function getSimHash2(Model\Resource $resource)
    {
        if ($resource instanceof Model\Bibtex) {
            return self::getBibtexSimHash2($resource);
        } else if ($resource instanceof Model\Bookmark) {
            return self::getBookmarkSimHash2($resource);
        }
        return null;
    }


    /**
     *
     * @param array <PersonUtils> $persons
     * @param bool $lastFirstNames
     * @param string $delimiter
     *
     * @return string|null
     */
    public static function serializePersonNames(array $persons, $lastFirstNames = self::DEFAULT_LAST_FIRST_NAMES, $delimiter = self::PERSON_NAME_DELIMITER)
    {

        if (empty($persons)) return null;

        $i = count($persons);
        $ret = "";
        foreach ($persons as $person) {
            --$i;
            $ret .= self::serializePersonName($person, $lastFirstNames);
            if ($i > 0) {
                $ret .= $delimiter;
            }
        }

        return $ret;
    }

    /**
     *
     * @param PersonUtils $person
     * @param bool $lastFirstName
     *
     * @return null|string
     */
    public static function serializePersonName(PersonUtils $person, $lastFirstName)
    {

        if (is_null($person)) return null;

        if ($lastFirstName) {
            $first = $person->getLastName();
            $last = $person->getFirstName();
            $delim = $person::LAST_FIRST_DELIMITER . " ";
        } else {
            $first = $person->getFirstName();
            $last = $person->getLastName();
            $delim = " ";
        }

        if (!empty($first)) {
            if (!empty($last)) {
                return $first . $delim . $last;
            }

            return $first;
        }
        if (!empty($last)) {
            return $last;
        }

        return null;
    }

    /**
     *
     * @param string $string
     *
     * @return string
     */
    public static function getNormalizedTitle($string)
    {

        if (!is_string($string)) return "";
        $str = StringUtils::removeNonNumbersOrLetters($string);

        return StringUtils::utf8_encode(mb_strtolower($str, mb_detect_encoding($str)));
    }

    /**
     *
     * @param array|Collection\ArrayList $persons – array of strings
     *
     * @return string [name1, name2, name3]
     */
    public static function getNormalizedPersons($persons)
    {

        return StringUtils::getStringFromList(self::normalizePersonList($persons));
    }

    /**
     * Normalizes a collection of persons by normalizing their names
     * and sorting them.
     *
     * @param array|Collection\ArrayList $persons - a list of persons.
     *
     * @return string A sorted set of normalized persons.
     */
    public static function normalizePersonList($persons)
    {
        $normalized = new Collection\ArrayList();
        foreach ($persons as $person) {

            $normalized->add($normalized->count(), self::normalizePerson($person));

        }

        return $normalized;
    }

    /**
     * Used for "sloppy" hashes, i.e., the inter hash.
     * <p>
     * The person name is normalized according to the following scheme:
     * <tt>x.last</tt>, where <tt>x</tt> is the first letter of the first name
     * and <tt>last</tt> is the last name.
     * </p>
     *
     * Example:
     * <pre>
     * Donald E. Knuth       --&gt; d.knuth
     * D.E.      Knuth       --&gt; d.knuth
     * Donald    Knuth       --&gt; d.knuth
     *           Knuth       --&gt; knuth
     * Knuth, Donald         --&gt; d.knuth
     * Knuth, Donald E.      --&gt; d.knuth
     * Maarten de Rijke      --&gt; m.rijke
     * Balby Marinho, Leandro--&gt; l.marinho
     * </pre>
     *
     * @param mixed PersonUtils|string $person
     *
     * @return string
     */
    public static function normalizePerson(PersonUtils $person)
    {


        $first = $person->getFirstName();
        $last = $person->getLastName();

        if (!empty($first) && empty($last)) {
            /*
             * Only the first name is given. This should practically never happen,
             * since we put such names into the last name field.
             */
            return strtolower(StringUtils::removeNonNumbersOrLettersOrDotsOrCommaOrSpace($first));
        }

        if (!empty($first) && !empty($last)) {
            /*
                * First and last given - default.
                * Take the first letter of the first name and append the last part
                * of the last name.
                */
            return self::getFirst($first) . "." . self::getLast($last);
        }

        if (!empty($last)) {
            /*
             * Only last name available - could be a "regular" name enclosed
             * in brackets.
             */
            return self::getLast($last);
        }

        return "";
    }

    /**
     * Returns the first letter of the first name, or an empty string, if no
     * such letter exists.
     *
     * @param string $first
     *
     * @return string
     */
    private static function getFirst($first)
    {
        $matches = [];
        if (preg_match(self::SINGLE_LETTER, $first, $matches)) {
            return strtolower(StringUtils::utf8_encode($matches[1]));
        }

        return "";
    }

    /**
     * Extracts from the last name the last part and cleans it. I.e., from
     * "van de Gruyter" we get "gruyter"
     *
     * @param string $last
     *
     * @return string
     */
    private static function getLast($last)
    {

        /**
         * @var string $trimmedLast
         */
        $trimmedLast = trim($last);

        /* to lower case */
        $cleanedLast = strtolower(

        /*
         * check encoding
         */
            StringUtils::utf8_encode(
            /*
             *  remove all unusual characters.
             */
                StringUtils::removeNonNumbersOrLettersOrDotsOrCommaOrSpace($trimmedLast)
            )
        );

        /*
         * If there is a space character, we take the last part of the name
         */
        $pos = strpos($cleanedLast, ' ');

        return $pos > 0 ? substr($cleanedLast, $pos + 1) : $cleanedLast;
    }

    /**
     *
     * @param string $year
     *
     * @return string
     */
    public static function getNormalizedYear($year)
    {

        return StringUtils::removeNonNumbers($year);
    }

    /**
     * @param array|Collection\ArrayList $persons
     *
     * @return string first person's last name.
     */
    public static function getFirstPersonsLastName($persons)
    {
        if (!empty($persons)) {
            /** @var PersonUtils $person */
            $person = $persons[0];

            return $person->getLastName();
        }

        return null;
    }

}

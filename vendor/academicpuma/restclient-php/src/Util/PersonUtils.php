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

class PersonUtils
{

    /**
     * delimiter between the parts of a person's name in the "Last, First" format.
     *
     * @var string
     */
    const LAST_FIRST_DELIMITER = ",";

    /**
     * Split pattern for person's name in the "Last, First" format
     *
     * @var string regexp
     */
    const LAST_COMMA_FIRST_SPLIT_PATTERN = "!([^,]+),\s?([^,]+)!";

    /**
     * Split pattern for person's name in the "First Last" format
     *
     * @var string regexp
     */
    const FIRST_SPACE_LAST_SPLIT_PATTERN = "!^(.*)\ (\p{L}+)$!";

    /**
     * Pattern for spliting names by one of the following characters
     *
     * - at least two space characters
     * - new line
     * - semicolon
     *
     * @var string regexp
     */
    const PERSON_NAME_DELIMITER = " and ";

    /**
     * @var string regexp
     */
    const HTML_LINE_BREAK = '!<br ?/?>!';

    const DEFAULT_LAST_FIRST_NAMES = true;

    /**
     *
     * @var string
     */
    protected $firstName;

    /**
     *
     * @var string
     */
    protected $lastName;


    /**
     * @param string $firstName
     * @param string $lastName
     *
     */
    public function __construct($firstName = '', $lastName = '')
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * tries to create an array of persons object from an array of strings
     *
     * @param Collection\ArrayList|array $persons_array
     * @return Collection\ArrayList
     * @throws
     */
    public static function createPersonsListFromArray($persons_array)
    {

        $persons = new Collection\ArrayList();

        foreach ($persons_array as $p) {

            $persons->add($persons->count(), self::createPersonFromString($p));

        }

        return $persons;
    }

    /**
     * @param Collection\ArrayList $authors
     *
     * @return string
     *
     */
    public static function concatAuthorList4BibTeX(Collection\ArrayList $authors)
    {

        $ret = "";
        $i = count($authors);
        foreach ($authors as $author) {
            /** @var PersonUtils $author */
            --$i;
            $ret .= $author->getLastName() . ", ";
            $ret .= $author->getFirstName();
            if ($i > 0) {
                $ret .= " and ";
            }
        }

        return $ret;
    }

    public static function createPersonsListFromString($personsString)
    {

        $personsString = StringUtils::cleanTitle2($personsString); //remove html/xml tags
        $personsArray = explode(self::PERSON_NAME_DELIMITER, $personsString);

        return self::createPersonsListFromArray($personsArray);
    }

    /**
     * @param $personString
     *
     * @return \AcademicPuma\RestClient\Util\PersonUtils
     */
    public static function createPersonFromString($personString)
    {

        $matches = [];

        $personString = StringUtils::cleanTitle2($personString); //remove html/xml tags

        if (preg_match(self::LAST_COMMA_FIRST_SPLIT_PATTERN, $personString, $matches)) {
            $person = new PersonUtils(trim($matches[2]), trim($matches[1]));

            return $person;

        } else if (preg_match(self::FIRST_SPACE_LAST_SPLIT_PATTERN, $personString, $matches)) {
            $person = new PersonUtils(trim($matches[1]), trim($matches[2]));

            return $person;

        } else {
            if (!empty($personString)) {
                $person = new PersonUtils("", $personString);

                return $person;

            }
        }

        throw new \InvalidArgumentException("Could not extract a person name.");
    }

    /**
     * @param Collection\ArrayList|array $persons
     * @param bool $lastFirstNames
     * @param string $delimiter
     *
     * @return string
     */
    public static function serializePersonNames(Collection\ArrayList $persons, $lastFirstNames =
    self::DEFAULT_LAST_FIRST_NAMES, $delimiter = self::PERSON_NAME_DELIMITER)
    {
        if (empty($persons)) return '';

        $str = '';
        $i = count($persons);

        foreach ($persons as $person) {
            --$i;
            $str .= self::serializePersonName($person, $lastFirstNames);
            if ($i > 0) {
                $str .= $delimiter;
            }
        }
        return $str;
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

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return $this->lastName . self::LAST_FIRST_DELIMITER . " " . $this->firstName;
    }

}

<?php

/*
 * restclient-php is a full-featured REST client  written in PHP
 * for PUMA and/or BibSonomy.
 * www.bibsonomy.org
 * www.academic-puma.de
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace AcademicPuma\RestClient\Util;


use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{


    protected $personStrings;

    protected $personStringsExpected;

    /**
     * @var ArrayList
     */
    protected $persons;

    public function setUp(): void
    {

        $this->personStrings = new Collection\ArrayList(['Lannister, Tywin', 'Greyjoy, Balon', 'Lord Walder Frey']);

        $this->personStringsExpected = new Collection\ArrayList(['Lannister, Tywin',
            'Greyjoy, Balon',
            'Frey, Lord Walder']);

        $this->persons = PersonUtils::createPersonsListFromArray($this->personStrings);

    }

    public function testCreatePersonsListFromArray()
    {


        $this->assertEquals($this->persons[0]->getFirstName(), 'Tywin');
        $this->assertEquals($this->persons[0]->getLastName(), 'Lannister');

        $this->assertEquals($this->persons[1]->getFirstName(), 'Balon');
        $this->assertEquals($this->persons[1]->getLastName(), 'Greyjoy');

        $this->assertEquals($this->persons[2]->getFirstName(), 'Lord Walder');
        $this->assertEquals($this->persons[2]->getLastName(), 'Frey');

    }

    /**
     *
     */
    public function testConcatAuthorList4BibTeX()
    {

        $personsBibtex = PersonUtils::concatAuthorList4BibTeX($this->persons);

        $this->assertEquals('Lannister, Tywin and Greyjoy, Balon and Frey, Lord Walder', $personsBibtex);
    }

    /**
     *
     */
    public function testSerializePersonNames()
    {

        $serialized = PersonUtils::serializePersonNames($this->persons, PersonUtils::DEFAULT_LAST_FIRST_NAMES, '; ');

        $this->assertEquals("Lannister, Tywin; Greyjoy, Balon; Frey, Lord Walder", $serialized);

        $serialized = PersonUtils::serializePersonNames($this->persons, false, '; ');

        $this->assertEquals("Tywin Lannister; Balon Greyjoy; Lord Walder Frey", $serialized);
    }

    /**
     *
     */
    public function testToString()
    {

        for ($i = 0; $i < $this->persons->count(); ++$i) {
            $person = $this->persons[$i];
            $this->assertEquals($this->personStringsExpected[$i], $person->toString());
        }
    }


}

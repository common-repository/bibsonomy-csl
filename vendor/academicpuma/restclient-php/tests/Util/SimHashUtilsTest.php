<?php
/*
 *
 * restclient-php is a full-featured REST client  written in PHP 
 * for PUMA and/or BibSonomy.
 * Copyright (C) 2015, Sebastian Böttger
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

use AcademicPuma\RestClient\Model\Bibtex;
use PHPUnit\Framework\TestCase;

class SimHashUtilsTest extends TestCase
{

    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::getNormalizedPersons
     */
    public function testGetNormalizedPersons()
    {

        $persons = [
            new PersonUtils("Bertrand Arthur William", "Russell"),
            new PersonUtils("Donald E.", "Knuth"),
            new PersonUtils("Tim", "Berners-Lee"),
            new PersonUtils("Thomas", "Vander Wal")
        ];

        $this->assertEquals('[b.russell,d.knuth,t.bernerslee,t.wal]', SimHashUtils::getNormalizedPersons($persons));
    }

    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::getNormalizedYear
     */
    public function testGetNormalizedYear()
    {
        $this->assertEquals('2014', SimHashUtils::getNormalizedYear('2014a'));
    }


    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::normalizePerson
     */
    public function testNormalizePerson()
    {
        $p = new PersonUtils("Bertrand Arthur William", "Russell");
        $this->assertEquals('b.russell', SimHashUtils::normalizePerson($p));
    }

    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::getFirstPersonsLastName
     */
    public function testGetFirstPersonsLastName()
    {
        $persons = [
            new PersonUtils("Bertrand Arthur William", "Russell"),
            new PersonUtils("Donald E.", "Knuth")
        ];
        $this->assertEquals('Russell', SimHashUtils::getFirstPersonsLastName($persons));
    }


    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::getNormalizedTitle
     */
    public function testGetNormalizedTitle()
    {
        $actual = SimHashUtils::getNormalizedTitle("Konzept und Umsetzung eines Tag-Recommenders für Video-Ressourcen am Beispiel UniVideo");
        $expected = "konzeptundumsetzungeinestagrecommendersfürvideoressourcenambeispielunivideo";

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::normalizePersonList
     */
    public function testNormalizePersonList()
    {

        $persons = [
            new PersonUtils("A"),
            new PersonUtils("B"),
            new PersonUtils("C")
        ];

        $normalizedPersons = SimHashUtils::normalizePersonList($persons);
        //error_log(print_r($normalizedPersons, true));
        $this->assertTrue($normalizedPersons[0] == "a");
        $this->assertTrue($normalizedPersons[1] == "b");
        $this->assertTrue($normalizedPersons[2] == "c");

        $this->assertEquals("[a,b,c]", StringUtils::getStringFromList($normalizedPersons));

        $persons = [
            new PersonUtils("Bertrand Arthur William", "Russell"),
            new PersonUtils("Donald E.", "Knuth"),
            new PersonUtils("Tim", "Berners-Lee"),
            new PersonUtils("Thomas", "Vander Wal")

        ];
        $normalizedPersons = SimHashUtils::normalizePersonList($persons);
        $this->assertEquals("[b.russell,d.knuth,t.bernerslee,t.wal]", StringUtils::getStringFromList($normalizedPersons));
    }

    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::getSimHash1
     */
    public function testGetSimHash1()
    {

        $person = new PersonUtils("Sebastian", "Böttger");

        $bibtex = new Bibtex();
        $bibtex->setTitle("Konzept und Umsetzung eines Tag-Recommenders für Video-Ressourcen am Beispiel UniVideo");
        $bibtex->setYear("2012");

        $bibtex->setAuthor($person->toString());

        // calculated hash from BibSonomy
        $bibsonomyHash = "8fd8ce9278d61f8bd5292d7aeab9aacd";

        //calculate hash
        $calculatedHash = SimHashUtils::getSimHash1($bibtex);

        //compare both hashes
        $this->assertEquals($bibsonomyHash, $calculatedHash);

        /*
         * okay, let's try with another more complex example
         */
        $personsArray = [
            new PersonUtils("G.", "Salton"),
            new PersonUtils("M.", "McGill")
        ];

        $persons = $personsArray[0]->toString() . " and " . $personsArray[1]->toString();

        $record = new Bibtex();
        $record->setTitle("Introduction to Modern Information Retrieval");
        $record->setYear("1983");
        $record->setAuthor($persons);


        //hash calculated from BibSonomy
        $bibsonomyHash = "90e5e9500c919499099da9517aa8163e";

        //calculate hash
        $calculatedHash = SimHashUtils::getSimHash1($record);

        //compare both hashes
        $this->assertEquals($bibsonomyHash, $calculatedHash);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\SimHashUtils::getSimHash2
     */
    public function testGetSimHash2()
    {


        $bibtex = new Bibtex();
        $bibtex->setAuthor('Doerfel, Stephan and Zoller, Daniel and Singer, Philipp and Niebler, Thomas and Hotho, Andreas and Strohmaier, Markus');
        $bibtex->setBooktitle('Proceedings of the 16th {LWA} Workshops: KDML, {IR} and FGWM, Aachen,               Germany, September 8-10, 2014.');
        $bibtex->setEditor('Seidl, Thomas and Hassani, Marwan and Beecks, Christian');
        $bibtex->setPages('18--19');
        $bibtex->setTitle('Evaluating Assumptions about Social Tagging - {A} Study of User Behavior
               in BibSonomy');
        $bibtex->setYear('2014');
        $bibtex->setInterHash('955cd7c6f7652b7c531b699464925b1f');
        $bibtex->setIntraHash('4b2e73c82b5a84e1959ad66aaad4a235');
        $bibtex->setEntrytype('inproceedings');
        $this->assertEquals($bibtex->getIntraHash(), SimHashUtils::getSimHash2($bibtex));

        // another test case where eighter author or editor is not set
        $bibtex2 = new Bibtex();
        $bibtex2->setAuthor('Doerfel, Stephan and Zoller, Daniel and Singer, Philipp and Niebler, Thomas and Hotho, Andreas and Strohmaier, Markus');
        $bibtex2->setTitle('Evaluating Assumptions about Social Tagging - {A} Study of User Behavior in BibSonomy');
        $bibtex2->setYear('2014');
        $bibtex2->setIntraHash('6ef1ab594b815ed2b63f95b820f7d0e3');
        $bibtex2->setEntrytype('book');
        $this->assertEquals($bibtex2->getIntraHash(), SimHashUtils::getSimHash2($bibtex2));
    }
}

<?php
/*
 *
 * restclient-php is a full-featured REST client  written in PHP 
 * for PUMA and/or BibSonomy.
 * Copyright (C) 2015, Sebastian Böttger
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

use AcademicPuma\RestClient\Model\Bibtex;
use PHPUnit\Framework\TestCase;

class BibtexModelUtilsTest extends TestCase
{

    /**
     * @covers AcademicPuma\RestClient\Util\BibtexModelUtils::generateBibtexKey
     */
    public function testGenerateBibtexKey()
    {

        $bibtexKey = BibtexUtils::generateBibtexKey(
            [new PersonUtils('Max', 'Mustermann')],
            [],
            '2011',
            'Ein aussagekräftiger Titel'
        );

        $this->assertEquals('mustermann2011aussagekraeftiger', $bibtexKey);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\BibtexModelUtils::appendBibtexKey
     */
    public function testAppendBibtexKey()
    {
        $bibtex = new Bibtex();
        $bibtex->setTitle('Ein aussagekräftiger Titel');
        $bibtex->setAuthor('Mustermann, Max');
        $bibtex->setYear('2011');

        BibtexUtils::appendBibtexKey($bibtex);

        $this->assertNotEmpty($bibtex->getBibtexKey());
        $this->assertEquals('mustermann2011aussagekraeftiger', $bibtex->getBibtexKey());

    }

    /**
     * @covers AcademicPuma\RestClient\Util\BibtexModelUtils::appendRequiredFields
     */
    public function testAppendRequiredFields()
    {

        $bibtex = new Bibtex();
        $bibtex->setEditor('Voldemorth, Lord');
        BibtexUtils::appendRequiredFields($bibtex);

        $this->assertNotEmpty($bibtex->getTitle());

        //author or editor must be set
        $this->assertTrue($bibtex->getAuthor() != '' || $bibtex->getEditor() != '');

        $this->assertNotEmpty($bibtex->getYear());

        $this->assertNotEmpty($bibtex->getEntrytype());


        $bibtex->setAuthor('');
        $bibtex->setEditor('');
        $bibtex->setYear("1997");
        $bibtex->setTitle("Harry Potter and the Philosopher’s Stone");
        BibtexUtils::appendRequiredFields($bibtex);
        $this->assertTrue($bibtex->getAuthor() != '' || $bibtex->getEditor() != '');
        $this->assertEquals("noauthor", $bibtex->getAuthor());
        $this->assertEquals("noauthor1997harry", $bibtex->getBibtexKey());
    }

    /**
     * @covers AcademicPuma\RestClient\Util\BibtexModelUtils::limitValueLength
     */
    public function testLimitValueLength()
    {
        $bibtex = new Bibtex();
        $bibtex->setAuthor('Greyjoy, Theon and Bolton, Ramsay');
        $bibtex->setEditor('Lannister, Tywin and Frey, Walder and Bolton, Roose');
        $bibtex->setTitle('Back where he belongs');
        $string = '';

        for ($i = 0; $i < 512; ++$i) {
            $ascii = (($i % 58) + 65);
            $string .= chr($ascii);
        }

        $this->assertTrue(strlen($string) > 256);

        $bibtex->setVolume($string);
        $bibtex->setChapter($string);
        $bibtex->setMonth($string);
        $bibtex->setPages($string);
        $bibtex->setNumber($string);
        $bibtex->setEntrytype($string);

        BibtexUtils::limitValueLength($bibtex);

        $this->assertTrue(strlen($bibtex->getVolume()) === BibtexUtils::$ATTRIBUTE_VALUE_LENGTH_LIMITS['volume']);
        $this->assertTrue(strlen($bibtex->getChapter()) === BibtexUtils::$ATTRIBUTE_VALUE_LENGTH_LIMITS['chapter']);
        $this->assertTrue(strlen($bibtex->getMonth()) === BibtexUtils::$ATTRIBUTE_VALUE_LENGTH_LIMITS['month']);
        $this->assertTrue(strlen($bibtex->getPages()) === BibtexUtils::$ATTRIBUTE_VALUE_LENGTH_LIMITS['pages']);
        $this->assertTrue(strlen($bibtex->getNumber()) === BibtexUtils::$ATTRIBUTE_VALUE_LENGTH_LIMITS['number']);
        $this->assertTrue(strlen($bibtex->getEntrytype()) === BibtexUtils::$ATTRIBUTE_VALUE_LENGTH_LIMITS['entrytype']);
    }

    /**
     * @covers AcademicPuma\RestClient\Util\BibtexModelUtils::appendMiscProp
     */
    public function testAppendMiscProp()
    {
        $bibtex = new Bibtex();

        // Test single misc property.
        $bibtex->setMisc(BibtexUtils::appendMiscProp($bibtex, 'foo', 'bar'));
        $this->assertTrue($bibtex->hasMiscField('foo'));
        $this->assertEquals("bar", $bibtex->getMiscField('foo'));
        $this->assertEquals('foo ' . BibtexUtils::ASSIGNMENT_OPERATOR . ' '
            . BibtexUtils::DEFAULT_OPENING_BRACKET . 'bar' . BibtexUtils::DEFAULT_CLOSING_BRACKET, $bibtex->getMisc());

        // Try to append property.
        $bibtex->setMisc(BibtexUtils::appendMiscProp($bibtex, 'foo1', 'bar1'));
        $this->assertTrue($bibtex->hasMiscField('foo'));
        $this->assertTrue($bibtex->hasMiscField('foo1'));
        $this->assertEquals("bar", $bibtex->getMiscField('foo'));
        $this->assertEquals("bar1", $bibtex->getMiscField('foo1'));
        $this->assertEquals('foo ' . BibtexUtils::ASSIGNMENT_OPERATOR . ' '
            . BibtexUtils::DEFAULT_OPENING_BRACKET . 'bar' . BibtexUtils::DEFAULT_CLOSING_BRACKET . ",\nfoo1 "
            . BibtexUtils::ASSIGNMENT_OPERATOR . ' ' . BibtexUtils::DEFAULT_OPENING_BRACKET . 'bar1'
            . BibtexUtils::DEFAULT_CLOSING_BRACKET, $bibtex->getMisc());
    }
}

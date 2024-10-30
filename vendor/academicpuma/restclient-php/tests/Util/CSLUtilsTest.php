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

use AcademicPuma\RestClient\Config\CSLType;
use AcademicPuma\RestClient\Config\Entrytype;
use PHPUnit\Framework\TestCase;

class CSLUtilsTest extends TestCase
{
    public function testGetEntrytype()
    {
        $this->assertEquals(CSLType::ARTICLE_JOURNAL, CSLUtils::getEntrytype(Entrytype::ARTICLE));
        $this->assertEquals(CSLType::PAPER_CONFERENCE, CSLUtils::getEntrytype(Entrytype::INPROCEEDINGS));
        $this->assertEquals(CSLType::CHAPTER, CSLUtils::getEntrytype(Entrytype::INBOOK));
        $this->assertEquals(CSLType::BOOK, CSLUtils::getEntrytype(Entrytype::BOOK));
        $this->assertEquals(CSLType::BOOK, CSLUtils::getEntrytype(Entrytype::PERIODICAL));
    }

    public function testConvertMonth()
    {
        $this->assertEquals(null, CSLUtils::convertMonth(null));
        $this->assertEquals(null, CSLUtils::convertMonth("nonexistent"));
        $this->assertEquals(null, CSLUtils::convertMonth("15"));
        $this->assertEquals(null, CSLUtils::convertMonth(15));
        $this->assertEquals(null, CSLUtils::convertMonth("-1"));
        $this->assertEquals(null, CSLUtils::convertMonth(-1));
        $this->assertEquals("01", CSLUtils::convertMonth("jan"));
        $this->assertEquals("01", CSLUtils::convertMonth("january"));
        $this->assertEquals("01", CSLUtils::convertMonth("January"));
        $this->assertEquals("1", CSLUtils::convertMonth(1));
        $this->assertEquals("1", CSLUtils::convertMonth("1"));
        $this->assertEquals("06", CSLUtils::convertMonth("jun"));
    }
}
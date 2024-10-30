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

class CSLTypeEntrytypeMapTest extends TestCase
{


    /**
     * @var CSLTypeEntrytypeMap
     */
    private $entrytypeMap;

    public function setUp(): void
    {
        $this->entrytypeMap = new CSLTypeEntrytypeMap();
    }

    public function testGetEntrytype()
    {

        $this->assertEquals($this->entrytypeMap->getEntrytype(Entrytype::ARTICLE), CSLType::ARTICLE_JOURNAL);
        $this->assertEquals($this->entrytypeMap->getEntrytype(Entrytype::INPROCEEDINGS), CSLType::PAPER_CONFERENCE);
        $this->assertEquals($this->entrytypeMap->getEntrytype(Entrytype::INBOOK), CSLType::CHAPTER);
        $this->assertEquals($this->entrytypeMap->getEntrytype(Entrytype::BOOK), CSLType::BOOK);
        $this->assertEquals($this->entrytypeMap->getEntrytype(Entrytype::PERIODICAL), CSLType::BOOK);
    }
}


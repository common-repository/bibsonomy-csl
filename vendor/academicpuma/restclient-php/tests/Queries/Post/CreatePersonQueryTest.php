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

namespace AcademicPuma\RestClient\Queries\Post;

use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\Person;
use PHPUnit\Framework\TestCase;

/**
 * Class CreatePersonQueryTest
 * @package AcademicPuma\RestClient\Queries\Post
 * @author kchoong
 */
class CreatePersonQueryTest extends TestCase
{

    private $createPersonQuery;

    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @param $personId
     * @throws UnsupportedOperationException
     */
    public function testExecute($accessor)
    {
        $person = new Person();
        $person->setMainName('Max Mustermann');
        $this->createPersonQuery = new CreatePersonQuery($person);

        // Check status code.
        $statusCode = $this->createPersonQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->createPersonQuery->isExecuted());

        // Is the resourcehash correct from the response?
        $this->assertStringStartsWith(TEST_PERSON_ID, $this->createPersonQuery->getPersonId());

    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }

}
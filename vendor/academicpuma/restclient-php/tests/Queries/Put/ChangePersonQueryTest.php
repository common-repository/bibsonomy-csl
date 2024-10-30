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

namespace AcademicPuma\RestClient\Queries\Put;


use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Queries\Get\GetPersonDetailsQuery;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangePersonQueryTest
 * @package AcademicPuma\RestClient\Queries\Put
 * @author kchoong
 */
class ChangePersonQueryTest extends TestCase
{

    /**
     * @var ChangePersonQuery query to test
     */
    private $changePersonQuery;

    /**
     * @var Person person to change for test
     */
    private $person;

    protected function setUp(): void
    {
        global $ACCESSOR_BASICAUTH;
        parent::setUp();
        $getPersonDetailsQuery = new GetPersonDetailsQuery(TEST_PERSON_ID);
        $statusCode = $getPersonDetailsQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        if ($statusCode === 200) {
            $this->person = $getPersonDetailsQuery->model();
        }
    }


    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @throws UnsupportedOperationException
     */
    public function testExecute($accessor)
    {
        $newValue = 'college';
        if ($this->person->getCollege() !== null) {
            $newValue = strrev($this->person->getCollege());
        }

        $this->person->setCollege($newValue);
        $this->changePersonQuery = new ChangePersonQuery(TEST_PERSON_ID, $this->person);

        // Check status code.
        $statusCode = $this->changePersonQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->changePersonQuery->isExecuted());

        // Is the resourcehash correct from the response?
        $this->assertEquals(TEST_PERSON_ID, $this->changePersonQuery->getPersonId());

    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }
}
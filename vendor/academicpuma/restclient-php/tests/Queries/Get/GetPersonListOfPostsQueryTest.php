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

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\Posts;
use DOMException;
use PHPUnit\Framework\TestCase;

/**
 * Class GetPersonListOfPostsQueryTest
 * @package AcademicPuma\RestClient\Queries\Get
 * @author kchoong
 */
class GetPersonListOfPostsQueryTest extends TestCase
{

    /**
     * @var GetPersonListOfPostsQuery query to test
     */
    private $getPersonListOfPostsQuery;

    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @param $personId
     * @param $start
     * @param $end
     * @throws DOMException
     * @throws UnsupportedOperationException
     */
    public function testExecute($accessor, $personId, $start, $end)
    {
        $this->getPersonListOfPostsQuery = new GetPersonListOfPostsQuery($personId, $start, $end);

        // Check status code.
        $statusCode = $this->getPersonListOfPostsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->getPersonListOfPostsQuery->isExecuted());

        // Check content.
        $body = $this->getPersonListOfPostsQuery->getBody();
        $this->assertEquals(preg_match('!<posts start=!', $body), 1);

        // Check type.
        $posts = $this->getPersonListOfPostsQuery->model();
        $this->assertTrue($posts instanceof Posts);
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_PERSON_ID, 0, 5)
        );
    }
}
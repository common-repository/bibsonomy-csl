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
use AcademicPuma\RestClient\Model\Projects;
use DOMException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class GetProjectsQueryTest
 * @package AcademicPuma\RestClient\Queries\Get
 * @author kchoong
 */
class GetProjectsQueryTest extends TestCase
{

    /**
     * @var GetProjectsQuery query to test
     */
    protected $getProjectsQuery;

    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @param $start
     * @param $end
     * @throws DOMException
     * @throws UnsupportedOperationException
     * @throws ReflectionException
     */
    public function testExecute($accessor, $start, $end)
    {
        $this->getProjectsQuery = new GetProjectsQuery($start, $end);

        // Check status code.
        $statusCode = $this->getProjectsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->getProjectsQuery->isExecuted());

        // Check content.
        $body = $this->getProjectsQuery->getBody();
        $this->assertStringContainsString('<projects>', $body);

        // Check type.
        $projects = $this->getProjectsQuery->model();
        $this->assertTrue($projects instanceof Projects);
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, 0, 3)
        );
    }

}

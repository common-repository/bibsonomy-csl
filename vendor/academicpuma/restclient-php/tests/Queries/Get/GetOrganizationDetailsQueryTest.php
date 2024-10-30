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
use AcademicPuma\RestClient\Model\Group;
use DOMException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class GetOrganizationDetailsQueryTest
 * @package AcademicPuma\RestClient\Queries\Get
 * @author kchoong
 */
class GetOrganizationDetailsQueryTest extends TestCase
{

    /**
     * @var GetOrganizationDetailsQuery query to test
     */
    private $getOrganizationDetailsQuery;

    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @throws UnsupportedOperationException
     * @throws DOMException
     * @throws ReflectionException
     */
    public function testExecute($accessor)
    {
        $this->getOrganizationDetailsQuery = new GetOrganizationDetailsQuery(TEST_ORGANIZATION_ID);

        // Check status code.
        $statusCode = $this->getOrganizationDetailsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->getOrganizationDetailsQuery->isExecuted());

        // Check content.
        $body = $this->getOrganizationDetailsQuery->getBody();
        $this->assertStringContainsString('<group name="' . TEST_ORGANIZATION_ID . '"', $body);

        // Check type.
        $organization = $this->getOrganizationDetailsQuery->model();
        $this->assertTrue($organization instanceof Group);
        $this->assertTrue($organization->isOrganization());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }

}
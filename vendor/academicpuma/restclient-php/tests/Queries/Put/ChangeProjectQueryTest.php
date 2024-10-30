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
use AcademicPuma\RestClient\Model\Project;
use AcademicPuma\RestClient\Queries\Get\GetProjectDetailsQuery;
use PHPUnit\Framework\TestCase;

/**
 * Class ChangePersonQueryTest
 * @package AcademicPuma\RestClient\Queries\Put
 * @author kchoong
 */
class ChangeProjectQueryTest extends TestCase
{

    private $changeProjectQuery;

    /**
     * @var Project
     */
    private $project;

    protected function setUp(): void
    {
        global $ACCESSOR_BASICAUTH;
        parent::setUp();
        $getProjectDetailsQuery = new GetProjectDetailsQuery(TEST_PROJECT_NAME);
        $statusCode = $getProjectDetailsQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        if ($statusCode === 200) {
            $this->project = $getProjectDetailsQuery->model();
        }
    }


    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @throws UnsupportedOperationException
     */
    public function testExecute($accessor)
    {
        $newValue = 'a good project description';
        if ($this->project->getDescription() !== null) {
            $newValue = strrev($this->project->getDescription());
        }
        $this->project->setDescription($newValue);
        $this->changeProjectQuery = new ChangeProjectQuery($this->project->getExternalId(), $this->project);

        // Check status code.
        $statusCode = $this->changeProjectQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->changeProjectQuery->isExecuted());

        // Is the resourcehash correct from the response?
        $this->assertEquals(TEST_PROJECT_NAME, $this->changeProjectQuery->getProjectId());

    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }

}
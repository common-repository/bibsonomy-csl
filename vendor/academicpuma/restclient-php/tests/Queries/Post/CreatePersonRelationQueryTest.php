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
use AcademicPuma\RestClient\Model\ResourceLink;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Queries\Delete\DeletePersonRelationQuery;
use GuzzleHttp\Exception\ServerException;
use PHPUnit\Framework\TestCase;

/**
 * Class CreatePersonRelationQueryTest
 * @package AcademicPuma\RestClient\Queries\Post
 * @author kchoong
 */
class CreatePersonRelationQueryTest extends TestCase
{

    /**
     * @var CreatePersonRelationQuery
     */
    private $createPersonRelationQuery;

    /**
     * @var ResourcePersonRelation
     */
    private $relation;

    protected function setUp(): void
    {
        global $ACCESSOR_BASICAUTH;
        parent::setUp();
        $this->relation = new ResourcePersonRelation();
        $resource = new ResourceLink();
        $resource->setInterHash(TEST_RESOURCE_HASH);
        $this->relation->setResourceLink($resource);
        $this->relation->setRelationType('author');
        $this->relation->setPersonIndex(100);
        try {
            $deletePersonRelationQuery = new DeletePersonRelationQuery(TEST_PERSON_ID,
                $this->relation->getResourceLink()->getInterHash(),
                $this->relation->getRelationType(),
                $this->relation->getPersonIndex());
            $deletePersonRelationQuery->execute($ACCESSOR_BASICAUTH->getClient());
        } catch (ServerException $e) {

        }
    }


    protected function tearDown(): void
    {
        global $ACCESSOR_BASICAUTH;
        parent::tearDown();
        try {
            $deletePersonRelationQuery = new DeletePersonRelationQuery(TEST_PERSON_ID,
                $this->relation->getResourceLink()->getInterHash(),
                $this->relation->getRelationType(),
                $this->relation->getPersonIndex());
            $deletePersonRelationQuery->execute($ACCESSOR_BASICAUTH->getClient());
        } catch (ServerException $e) {

        }
    }


    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @throws UnsupportedOperationException
     */
    public function testExecute($accessor)
    {
        $this->createPersonRelationQuery = new CreatePersonRelationQuery(TEST_PERSON_ID, $this->relation);

        // Check status code.
        $statusCode = $this->createPersonRelationQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->createPersonRelationQuery->isExecuted());

        // Is the resourcehash correct from the response?
        $this->assertEquals(TEST_RESOURCE_HASH, $this->createPersonRelationQuery->getResourceHash());

    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }

}
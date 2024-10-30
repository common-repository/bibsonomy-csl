<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Authentication\Accessor;
use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetGroupDetailsQueryTest extends TestCase
{

    /**
     * @var GetGroupDetailsQuery
     */
    protected $getGroupDetailsQuery;

    /**
     * @dataProvider executeProvider
     *
     * @param Accessor $accessor
     * @param          $groupName
     *
     * @throws \AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException
     */
    public function testExecute(Accessor $accessor, $groupName)
    {

        $this->getGroupDetailsQuery = new GetGroupDetailsQuery($groupName);

        // Check status code.
        $statusCode = $this->getGroupDetailsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $this->getGroupDetailsQuery->getBody();
        $this->assertContains('<group name="' . TEST_GROUP . '"', $body);

        // Check type.
        $group = $this->getGroupDetailsQuery->model();
        $this->assertTrue($group instanceof Model\Group);

        // Is executed flag set?
        $this->assertTrue($this->getGroupDetailsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_GROUP)
        );
    }
}

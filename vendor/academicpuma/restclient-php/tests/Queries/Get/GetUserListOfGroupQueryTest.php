<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-04-06 at 11:46:31.
 */
class GetUserListOfGroupQueryTest extends TestCase
{

    /**
     * @var GetUserListOfGroupQuery
     */
    protected $getUserListOfGroupQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $groupname, $start, $end)
    {

        $this->getUserListOfGroupQuery = new GetUserListOfGroupQuery($groupname, $start, $end);

        // Check status code.
        $statusCode = $this->getUserListOfGroupQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $this->getUserListOfGroupQuery->getBody();
        $this->assertContains('<users start="1"', $body);

        // Check type.
        $users = $this->getUserListOfGroupQuery->model();
        $this->assertTrue($users instanceof Model\Users);

        // Is executed flag set?
        $this->assertTrue($this->getUserListOfGroupQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_GROUP, 1, 5)
        );
    }
}

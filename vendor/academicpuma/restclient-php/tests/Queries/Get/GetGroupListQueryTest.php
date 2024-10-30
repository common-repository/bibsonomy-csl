<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetGroupListQueryTest extends TestCase
{

    /**
     * @var GetGroupListQuery
     */
    protected $getGroupListQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $start, $end)
    {

        $this->getGroupListQuery = new GetGroupListQuery($start, $end);

        // Check status code.
        $statusCode = $this->getGroupListQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $this->getGroupListQuery->getBody();
        $this->assertContains('<group name="dlt-forschung"', $body);

        // Check type.
        $groups = $this->getGroupListQuery->model();
        $this->assertTrue($groups instanceof Model\Groups);

        foreach ($groups as $group) {
            $this->assertTrue($group instanceof Model\Group);
        }

        //$this->assertContains('<user name="sboettger"', $body);

        // Is executed flag set?
        $this->assertTrue($this->getGroupListQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, 0, 5)
        );
    }
}

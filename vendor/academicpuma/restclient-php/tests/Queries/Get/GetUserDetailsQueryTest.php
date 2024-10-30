<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetUserDetailsQueryTest extends TestCase
{

    /**
     * @var GetUserDetailsQuery
     */
    protected $getUserDetailsQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $username)
    {

        $this->getUserDetailsQuery = new GetUserDetailsQuery($username);

        // Check status code.
        $statusCode = $this->getUserDetailsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $this->getUserDetailsQuery->getBody();
        $this->assertContains('<user name="' . TEST_USER_ID . '"', $body);

        // Check type.
        $user = $this->getUserDetailsQuery->model();
        $this->assertTrue($user instanceof Model\User);

        // Is executed flag set?
        $this->assertTrue($this->getUserDetailsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID)
        );
    }
}

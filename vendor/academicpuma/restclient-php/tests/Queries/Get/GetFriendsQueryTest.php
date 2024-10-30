<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetFriendsQueryTest extends TestCase
{

    /**
     * @var GetFriendsQuery
     */
    protected $getFriendsQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExcecute($accessor, $username, $relation, $start, $end)
    {

        $getFriendsQuery = new GetFriendsQuery($username, $start, $end, $relation);

        // Excecute and check status code.
        $statusCode = $getFriendsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $getFriendsQuery->getBody();

        if ($relation === Config\RESTConfig::INCOMING_ATTRIBUTE_VALUE_RELATION) {
            $this->assertContains('<user name="' . TEST_FRIEND_INCOMING . '"', $body);
        } else {
            $this->assertContains('<user name="' . TEST_FRIEND_OUTGOING . '"', $body);
        }

        // Check type.
        /**
         * @var Model\Users $users
         */
        $users = $getFriendsQuery->model();

        $this->assertTrue($users instanceof Model\Users);
        $this->assertTrue($users->count() >= 1);


        foreach ($users as $user) {
            $this->assertTrue($user instanceof Model\User);
        }

        // Is executed flag set?
        $this->assertTrue($getFriendsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, Config\RESTConfig::INCOMING_ATTRIBUTE_VALUE_RELATION, NULL, NULL)
        );
    }
}

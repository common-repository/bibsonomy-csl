<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetPostDetailsQueryTest extends TestCase
{

    /**
     * @var GetPostDetailsQuery
     */
    protected $getPostsDetailsQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $username, $resourcehash)
    {

        $this->getPostsDetailsQuery = new GetPostDetailsQuery($username, $resourcehash);

        // Check status code.
        $statusCode = $this->getPostsDetailsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check type.
        $post = $this->getPostsDetailsQuery->model();
        $this->assertTrue($post instanceof Model\Post);

        // Check if hash is requested hash.
        $this->assertEquals(TEST_RESOURCE_HASH, $post->getResource()->getIntraHash());

        // Is executed flag set?
        $this->assertTrue($this->getPostsDetailsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, TEST_RESOURCE_HASH)
        );
    }
}

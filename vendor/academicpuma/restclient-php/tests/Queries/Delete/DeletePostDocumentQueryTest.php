<?php

namespace AcademicPuma\RestClient\Queries\Delete;

use AcademicPuma\RestClient\Authentication\Accessor;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;

class DeletePostDocumentQueryTest extends TestCase
{

    /**
     * @var DeletePostDocumentQuery
     */
    protected $deletePostDocumentQuery;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {

        global $ACCESSOR_BASICAUTH;

        // Create post document to test if it can be deleted.

        $createPostDocumentQuery = new Queries\Post\CreatePostDocumentQuery(
            TEST_USER_ID, TEST_RESOURCE_HASH, TEST_FILE_NAME, TEST_FILE_PATH);

        $statusCode = $createPostDocumentQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);
    }

    /**
     * @dataProvider executeProvider
     *
     * @param Accessor $accessor
     * @param string $userName
     * @param string $resourceHash
     * @param string $fileName
     */
    public function testExecute(Accessor $accessor, $userName, $resourceHash, $fileName)
    {

        $this->deletePostDocumentQuery = new DeletePostDocumentQuery($userName, $resourceHash, $fileName);

        // Check statuscode.
        $statusCode = $this->deletePostDocumentQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, TEST_RESOURCE_HASH, TEST_FILE_NAME),
            array($ACCESSOR_OAUTH, TEST_USER_ID, TEST_RESOURCE_HASH, TEST_FILE_NAME)
        );
    }

}

<?php

namespace AcademicPuma\RestClient\Queries\Post;

use AcademicPuma\RestClient\Authentication\Accessor;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;

class CreatePostDocumentQueryTest extends TestCase
{

    /**
     * @var CreatePostDocumentQuery
     */
    protected $createPostDocumentQuery;

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {

        global $ACCESSOR_BASICAUTH;

        $deletePostDocumentQuery = new Queries\Delete\DeletePostDocumentQuery(
            TEST_USER_ID, TEST_RESOURCE_HASH, TEST_FILE_NAME);

        // Check statuscode.
        $statusCode = $deletePostDocumentQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);
    }

    /**
     * @dataProvider executeProvider
     *
     * @param Accessor $accessor
     * @param string $userName
     * @param string $resourceHash
     * @param string $fileName
     * @param string $filePath
     */
    public function testExecute(Accessor $accessor, $userName, $resourceHash, $fileName, $filePath)
    {

        $path = realpath(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $filePath));

        $this->createPostDocumentQuery = new CreatePostDocumentQuery($userName, $resourceHash, $fileName, $path);

        // Check status code.
        $statusCode = $this->createPostDocumentQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->createPostDocumentQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, TEST_RESOURCE_HASH, TEST_FILE_NAME, TEST_FILE_PATH),
        );
    }
}

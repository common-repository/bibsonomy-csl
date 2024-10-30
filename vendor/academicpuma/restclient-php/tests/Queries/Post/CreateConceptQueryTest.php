<?php

namespace AcademicPuma\RestClient\Queries\Post;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;

class CreateConceptQueryTest extends TestCase
{

    /**
     * @var CreateConceptQuery
     */
    protected $createConceptQuery;

    /**
     *
     * @var string
     */
    private $conceptName = 'newConcept';

    /**
     *
     * @var Model\Tag
     */
    private $tag;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {

        $subTag = new Model\Tag();
        $subTag->setName('subTag');

        $this->tag = new Model\Tag();
        $this->tag->setName($this->conceptName);
        $this->tag->addSubTag($subTag);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        global $ACCESSOR_BASICAUTH;

        $deleteConceptQuery = new Queries\Delete\DeleteConceptQuery($this->conceptName, TEST_USER_ID);

        // Execute and check status code.
        $statusCode = $deleteConceptQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($deleteConceptQuery->isExecuted());
    }

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $userName)
    {

        $this->createConceptQuery = new CreateConceptQuery($this->tag, $userName);

        // Execute and check status code.
        $statusCode = $this->createConceptQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->createConceptQuery->isExecuted());

        // Check if the concept was created in the expected way.
        $getConceptQuery = new Queries\Get\GetConceptsQuery(Config\Resourcetype::BIBTEX, Config\RESTConfig::USERS_URL,
            TEST_USER_ID, NULL, [$this->conceptName], Config\TagStatus::PICKED, NULL, NULL);

        // Check status code.
        $statusCodeGet = $getConceptQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCodeGet);

        // Check content.
        $expectedXML = '<tag name="' . $this->conceptName . '"';
        $responseBody = $getConceptQuery->getBody();
        $this->assertContains($expectedXML, $responseBody);

        // Check type.
        $tags = $getConceptQuery->model();
        foreach ($tags as $tag) {
            $this->assertTrue($tag instanceof Model\Tag);
        }
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID)
        );
    }
}

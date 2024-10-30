<?php

namespace AcademicPuma\RestClient\Queries\Put;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;


class ChangeConceptQueryTest extends TestCase
{

    /**
     * @var ChangeConceptQuery
     */
    protected $changeConceptQuery;

    /**
     *
     * @var string
     */
    private $conceptName = 'newConcept';

    /**
     *
     * @var string
     */
    private $changedSubTagName = 'changed';

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

        global $ACCESSOR_BASICAUTH;

        // First a concept has to be created in order to check if it can be changed.
        $subTag = new Model\Tag();
        $subTag->setName('subTag');

        $this->tag = new Model\Tag();
        $this->tag->setName($this->conceptName);
        $this->tag->addSubTag($subTag);

        $createConceptQuery = new Queries\Post\CreateConceptQuery($this->tag, TEST_USER_ID, Config\RESTConfig::USERS_URL);

        // Execute and check status code.
        $statusCode = $createConceptQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);

        $subTag->setName($this->changedSubTagName);
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
    public function testExecute($accessor, $username, $operation)
    {

        $this->changeConceptQuery = new ChangeConceptQuery($this->tag, $username, $operation);

        // Execute and check status code.
        $statusCode = $this->changeConceptQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->changeConceptQuery->isExecuted());

        // Check if the post was created in the expected way.
        $getConceptsQuery = new Queries\Get\GetConceptsQuery(
            Config\Resourcetype::BIBTEX,
            Config\RESTConfig::USERS_URL,
            TEST_USER_ID,
            NULL, [$this->changedSubTagName],
            Config\TagStatus::PICKED, NULL, NULL
        );

        // Execute and check status code.
        $statusCodeGet = $getConceptsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCodeGet);

        // TODO: Es spielt eine Rolle, in welcher Reihenfolge model und getBody aufgerufen werden.
        // Check content.

        // Check type.
        $concepts = $getConceptsQuery->model();
        foreach ($concepts as $concept) {
            $this->assertTrue($concept instanceof Model\Tag);
        }

        $expectedXML = '<tag name="changed"';
        $responseBody = $getConceptsQuery->getBody();
        $this->assertContains($expectedXML, $responseBody);

    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, NULL)
        );
    }
}

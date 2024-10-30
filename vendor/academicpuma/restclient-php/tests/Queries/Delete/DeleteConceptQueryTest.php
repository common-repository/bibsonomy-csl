<?php

namespace AcademicPuma\RestClient\Queries\Delete;

use AcademicPuma\RestClient\Authentication\Accessor;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;


class DeleteConceptQueryTest extends TestCase
{

    /**
     * @var DeleteConceptQuery
     */
    protected $deleteConceptQuery;

    private $conceptName = 'newConcept';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {

        global $ACCESSOR_BASICAUTH;

        // Create a concept in order to test if it can be deleted successfully.
        $tag = new Model\Tag();
        $subTag = new Model\Tag();

        $tag->setName($this->conceptName);
        $subTag->setName('subTag');
        $tag->addSubTag($subTag);

        $createConceptQuery = new Queries\Post\CreateConceptQuery($tag, TEST_USER_ID);
        $createConceptQuery->execute($ACCESSOR_BASICAUTH->getClient());
    }

    /**
     * @covers       AcademicPuma\RestClient\Queries\Delete\DeleteConceptQuery::execute
     * @dataProvider executeProvider
     *
     * @param Accessor $accessor
     * @param string $conceptName
     * @param string $username
     */
    public function testExecute(Accessor $accessor, $conceptName, $username)
    {

        // Create query.
        $this->deleteConceptQuery = new DeleteConceptQuery($conceptName, $username);

        // Check status code.
        $statusCode = $this->deleteConceptQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->deleteConceptQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, $this->conceptName, TEST_USER_ID)
        );
    }
}

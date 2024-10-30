<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetTagDetailsQueryTest extends TestCase
{

    /**
     * @var GetTagDetailsQuery
     */
    protected $getTagDetailsQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $tagName)
    {

        $this->getTagDetailsQuery = new GetTagDetailsQuery($tagName);

        // Check status code.
        $statusCode = $this->getTagDetailsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check type.
        $tag = $this->getTagDetailsQuery->model();
        $this->assertTrue($tag instanceof Model\Tag);

        // Is executed flag set?
        $this->assertTrue($this->getTagDetailsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, 'myown')
        );
    }
}

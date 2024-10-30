<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use DOMException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class GetConceptsQueryTest extends TestCase
{

    /**
     * @dataProvider executeProvider
     * @param $accessor
     * @param $resourceType
     * @param $grouping
     * @param $groupingName
     * @param $regex
     * @param $tags
     * @param $status
     * @param $start
     * @param $end
     * @throws UnsupportedOperationException
     * @throws DOMException
     * @throws GuzzleException
     * @throws ReflectionException
     */
    public function testExecute($accessor, $resourceType, $grouping, $groupingName, $tags, $regex, $status, $start, $end)
    {

        $getConceptsQuery = new GetConceptsQuery($resourceType, $grouping, $groupingName, $tags, $regex, $status, $start, $end);

        // Check status code.
        $statusCode = $getConceptsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);


        // Check content.
        $expectedXML = '<tag name="folksonomy"';
        $responseBody = $getConceptsQuery->getBody();
        $this->assertContains($expectedXML, $responseBody);

        // Check type.
        $conceptTags = $getConceptsQuery->model();
        foreach ($conceptTags as $tag) {
            $this->assertTrue($tag instanceof Model\Tag);
        }

        // Is executed flag set?
        $this->assertTrue($getConceptsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, Config\Resourcetype::BIBTEX, Config\RESTConfig::USERS_URL, TEST_USER_ID, [],
                NULL, NULL, 0, 10)
        );
    }
}

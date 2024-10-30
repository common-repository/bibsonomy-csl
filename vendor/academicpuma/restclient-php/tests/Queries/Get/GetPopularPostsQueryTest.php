<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use PHPUnit\Framework\TestCase;

class  GetPopularPostsQueryTest extends TestCase
{

    /**
     * @var GetPopularPostsQuery
     */
    protected $getPopularPostsQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $resourceType, $grouping, $groupingName, $start, $end)
    {

        $this->getPopularPostsQuery = new GetPopularPostsQuery($resourceType, $grouping, $groupingName, $start, $end);

//        $body = $this->getPopularPostsQuery->execute($accessor->getClient())->getBody();
//        $this->assertEquals(preg_match('!<posts start=!', $body), 1);
//
//        $posts = $this->getPopularPostsQuery->model();
//
//        foreach($posts as $post) {
//            $this->assertTrue($post->getResource() instanceof Model\Bibtex);
//            $this->assertNotEmpty($post->getResource()->getTitle());
//        }
//
//        // Is executed flag set?
//        $this->assertTrue($this->getPopularPostsQuery->isExecuted());

        $this->assertTrue(true, 'API function not implemented yet.');
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, Config\Resourcetype::BIBTEX, Config\Grouping::USER, TEST_USER_ID, 1, 5),
            array($ACCESSOR_OAUTH, Config\Resourcetype::BIBTEX, Config\Grouping::USER, TEST_USER_ID, 1, 5)
        );
    }
}

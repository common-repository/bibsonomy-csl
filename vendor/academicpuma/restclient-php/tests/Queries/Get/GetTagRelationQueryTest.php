<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use PHPUnit\Framework\TestCase;

class GetTagRelationQueryTest extends TestCase
{

    /**
     * @var GetTagRelationQuery
     */
    protected $getTagRelationQuery;

    /**
     * @throws \AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException
     */
    public function testRelatedTagRelation()
    {
        global $ACCESSOR_BASICAUTH;
        $getTagRelationQuery = new GetTagRelationQuery(Config\Grouping::USER, TEST_USER_ID, Config\TagRelation::RELATED, ['puma'], Config\TagOrder::FREQUENCY, 0, 20);

        $tags = $getTagRelationQuery->execute($ACCESSOR_BASICAUTH->getClient())->model();
        $this->assertInstanceOf('\\AcademicPuma\\RestClient\\Model\\Tags', $tags);

        print $tags;

        $expectedTags = explode(" ", TEST_TAG_RELATION_RELATED);
        foreach ($expectedTags as $tag) {
            $this->assertTrue(in_array($tag, $tags->toArray()));
        }
    }

    public function testSimilarTagRelation()
    {
        global $ACCESSOR_BASICAUTH;
        $getTagRelationQuery = new GetTagRelationQuery(Config\Grouping::GROUPING, Config\Grouping::GROUPING_VALUE_ALL, Config\TagRelation::SIMILAR, ['folksonomy'], Config\TagOrder::FREQUENCY, 0, 20);

        $tags = $getTagRelationQuery->execute($ACCESSOR_BASICAUTH->getClient())->model();

        $this->assertInstanceOf('\\AcademicPuma\\RestClient\\Model\\Tags', $tags);

        $expectedTags = ['recommender', 'tag', 'social', 'bookmarking'];

        foreach ($expectedTags as $tag) {
            $this->assertTrue(in_array($tag, $tags->toArray()));
        }
    }

//    public function testMultipleTagsRelation() {
//        global $ACCESSOR_OAUTH;
//        $getTagRelationQuery = new GetTagRelationQuery(Config\Grouping::GROUP, TEST_GROUP, Config\TagRelation::RELATED, ['folksonomy', 'tagging', 'social'], Config\TagOrder::ALPHANUMERIC, 0, 4);
//
//        $tags = $getTagRelationQuery->execute($ACCESSOR_OAUTH->getClient())->model();
//
//        $this->assertInstanceOf('\\AcademicPuma\\RestClient\\Model\\Tags', $tags);
//
//        $expectedTags = ['myown', 'bookmarking', 'web', 'recommender'];
//
//        foreach ($expectedTags as $tag) {
//            $this->assertTrue(in_array($tag, $tags->asArray()));
//        }
//    }
}

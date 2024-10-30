<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Model;
use PHPUnit\Framework\TestCase;

class GetTagsQueryTest extends TestCase
{

    /**
     * @var GetTagsQuery
     */
    protected $getTagsQuery;

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $grouping, $groupingName, $regex, $order, $start, $end)
    {

        $this->getTagsQuery = new GetTagsQuery($grouping, $groupingName, $regex, $order, $start, $end);

        // Check status code.
        $statusCode = $this->getTagsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $this->getTagsQuery->getBody();
        $this->assertContains('<tags start="' . $start . '"', $body);

        // Check type.
        $tags = $this->getTagsQuery->model();
        $this->assertTrue($tags instanceof Model\Tags);

        foreach ($tags as $tag) {
            $this->assertTrue($tag instanceof Model\Tag);
            $this->assertNotEmpty($tag->getName());
        }

        // Is executed flag set?
        $this->assertTrue($this->getTagsQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return [
            [$ACCESSOR_BASICAUTH, Config\Grouping::USER, TEST_USER_ID, NULL, NULL, 1, 5],
            [$ACCESSOR_BASICAUTH, Config\Grouping::GROUPING, Config\Grouping::GROUPING_VALUE_ALL, NULL, Config\TagOrder::ALPHANUMERIC, 0, 20]
        ];
    }

    /**
     * @dataProvider sortProvider
     *
     */
    public function testSort($accessor, $grouping, $groupingName, $regex, $order, $start, $end)
    {
        $this->getTagsQuery = new GetTagsQuery($grouping, $groupingName, $regex, $order, $start, $end);

        /** @var Model\Tags $tags */
        $tags = $this->getTagsQuery->execute($accessor->getClient())->model();

        $tags->sort('name', Config\Sorting::ORDER_ASC);

        $oldTag = null;
        foreach ($tags as $tag) {
            if ($oldTag != null) {
                $this->assertTrue(strcmp($oldTag, $tag) <= 0);
            }
            $oldTag = $tag;
        }

        $tags->sort('usercount', Config\Sorting::ORDER_DESC);
        $oldTag = null;
        foreach ($tags as $tag) {
            if ($oldTag != null) {
                $this->assertTrue($oldTag->getUsercount() >= $tag->getUsercount());
            }
            echo $tag->getUsercount() . ': ' . $tag . "\n";
            $oldTag = $tag;
        }

        $tags->sort('globalcount', Config\Sorting::ORDER_ASC);
        $oldTag = null;
        foreach ($tags as $tag) {
            if ($oldTag != null) {
                $this->assertTrue($oldTag->getGlobalcount() <= $tag->getGlobalcount());
            }
            echo $tag->getGlobalcount() . ': ' . $tag . "\n";
            $oldTag = $tag;
        }
    }

    public function sortProvider()
    {
        global $ACCESSOR_BASICAUTH;

        return [[$ACCESSOR_BASICAUTH, Config\Grouping::USER, TEST_USER_ID, NULL, NULL, 1, 50]];
    }
}

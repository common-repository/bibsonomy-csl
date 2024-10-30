<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Config\Sorting;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use DOMException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class GetPostsQueryTest extends TestCase
{

    /**
     * @dataProvider executeProvider
     * @param string $resourceType the requested resourcetype
     * @param string $grouping determines to which group $groupingName belongs
     * @param string $groupingName name of user or group who/which created the posts
     * @param array $tags a list of tags filtering the posts
     * @param string|null $hash interhash of resource
     * @param string|null $search full string search currently not implemented
     * @param array $sortKeys a list of keys to sort the posts by
     * @param array $sortOrders a list of sort orders to set the order for the keys
     * @param string $searchType (local|searchindex)
     * @param int $start start index
     * @param int $end end index
     * @param string $format (xml|json|csl|bibtex|endnote)
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     * @throws DOMException
     * @throws ReflectionException
     */
    public function testExecute($accessor,
                                string $resourceType,
                                string $grouping,
                                string $groupingName,
                                array $tags,
                                ?string $hash,
                                ?string $search,
                                array $sortKeys,
                                array $sortOrders,
                                string $searchType,
                                int $start,
                                int $end,
                                string $format)
    {
        $getPostsQuery = new GetPostsQuery($resourceType, $grouping, $groupingName, $tags, $hash, $search, $sortKeys, $sortOrders, $searchType, $start, $end, $format);

        // Check status code.
        $statusCode = $getPostsQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Check content.
        $body = $getPostsQuery->getBody();
        $this->assertEquals(1, preg_match('!<posts start=!', $body));

        // Check type.
        $posts = $getPostsQuery->model();

        if ($grouping === Grouping::GROUPING) {
            $this->assertTrue($posts->count() === 40);
        }

        foreach ($posts as $post) {
            $this->assertTrue($post->getResource() instanceof Model\Bibtex);
            $this->assertNotEmpty($post->getResource()->getTitle());
        }

        // Is executed flag set?
        $this->assertTrue($getPostsQuery->isExecuted());
    }

    public function executeProvider(): array
    {
        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return [
            [$ACCESSOR_BASICAUTH, Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, ['myown', 'test'], null, null, [], [], 'local', 0, 0, 'xml'],
            // [$ACCESSOR_OAUTH, Resourcetype::BIBTEX, Grouping::GROUPING, Grouping::GROUPING_VALUE_ALL, [], null, null, [], [], 'local', 0, 40, 'xml'],
            [$ACCESSOR_BASICAUTH, Resourcetype::BIBTEX, Grouping::GROUPING, Grouping::GROUPING_VALUE_ALL, [], null, null, [], [], 'local', 0, 40, 'xml']
        ];
    }

    /**
     * @dataProvider sortingProvider
     * @param $accessor
     * @param $resourceType
     * @param $grouping
     * @param $groupingName
     * @param array $tags
     * @param $hash
     * @param $search
     * @param $format
     * @param $start
     * @param $end
     * @throws DOMException
     * @throws GuzzleException
     * @throws ReflectionException
     * @throws UnsupportedOperationException
     */
    public function testSorting($accessor, $resourceType, $grouping, $groupingName, array $tags, $hash, $search, $start, $end, $format)
    {
        $getPostsQuery = new GetPostsQuery($resourceType, $grouping, $groupingName, $tags, $hash, $search, $start, $end, $format);

        $getPostsQuery->execute($accessor->getClient());

        $posts = $getPostsQuery->model();

        echo "unordered list:\n";
        foreach ($posts as $post) {
            echo $post->getResource()->getTitle() . "\n";
        }
        echo "\n\n";

        $posts->sort('author', Sorting::ORDER_DESC);
        echo "ordered by author (desc):\n";
        foreach ($posts as $post) {
            echo $post->getResource()->getAuthor() . "\n";
        }
        echo "\n\n";
        $oldPost = null;
        foreach ($posts as $post) {
            if ($oldPost != null) {
                $this->assertTrue(strcmp($oldPost->getResource()->getAuthor(), $post->getResource()->getAuthor()) >= 0);
            }
            $oldPost = $post;
        }

        $posts->sort('title');
        echo "ordered by title:\n";
        foreach ($posts as $post) {
            echo $post->getResource()->getTitle() . "\n";
        }
        echo "\n\n";
        $oldPost = null;
        foreach ($posts as $post) {
            if ($oldPost != null) {
                $this->assertTrue(strcmp($oldPost->getResource()->getTitle(), $post->getResource()->getTitle()) <= 0);
            }
            $oldPost = $post;
        }

        $posts->sort('year', Sorting::ORDER_DESC);
        echo "ordered by year (desc):\n";
        foreach ($posts as $post) {
            echo $post->getResource()->getYear() . " " . $post->getResource()->getTitle() . "\n";
        }
        $oldPost = null;
        foreach ($posts as $post) {
            if ($oldPost != null) {
                $this->assertTrue($oldPost->getResource()->getYear() >= $post->getResource()->getYear());
            }
            $oldPost = $post;
        }

        $typeOrder = ['presentation', 'book', 'article', 'inproceedings', 'phdthesis'];
        $posts->sort('entrytype', Sorting::ORDER_ASC, $typeOrder);
        echo "ordered by entrytype\n";
        foreach ($posts as $post) {
            echo $post->getResource()->getEntrytype() . "\n";
        }
        $oldPost = null;
        foreach ($posts as $post) {

            if ($oldPost != null) {
                $key_a = array_keys($typeOrder, $oldPost->getResource()->getEntrytype())[0];
                $key_b = array_keys($typeOrder, $post->getResource()->getEntrytype())[0];
                $this->assertTrue($key_a <= $key_b);
            }
            $oldPost = $post;
        }
    }

    public function sortingProvider(): array
    {
        global $ACCESSOR_BASICAUTH;

        return [
            [$ACCESSOR_BASICAUTH, Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, [], null, null, [], [], 'local', 0, 20, 'xml']
        ];
    }
}

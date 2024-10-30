<?php

namespace AcademicPuma\RestClient\Queries\Post;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;

class CreatePostQueryTest extends TestCase
{

    /**
     * @var createPostQuery
     */
    protected $createPostQuery;

    /**
     *
     * @var string $tagName name of the tag
     */
    private $tagName = 'test-create-post';

    /**
     *
     * @var string $resourceHash
     */
    private $resourceHash;

    /**
     *
     * @var \AcademicPuma\RestClient\Model\Post
     */
    private $post;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {

        // Init user.
        $user = new Model\User();
        $user->setName(TEST_USER_ID);

        // Init tag.
        $tag = new Model\Tag();
        $tag->setName($this->tagName);

        // Init group.
        $group = new Model\Group();
        $group->setName('private');

        // Init bookmark.
        $bookmark = new Model\Bookmark();
        $bookmark->setTitle('RestClient Repository');
        $bookmark->setUrl('https://bitbucket.org/bibsonomy/restclient-php');

        // Init posts.
        $this->post = new Model\Post();
        $this->post->setUser($user);
        $this->post->addTag($tag);
        $this->post->setDescription('Test description.');
        $this->post->setResource($bookmark);
        $this->post->setGroup($group);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {

        global $ACCESSOR_BASICAUTH;

        $deletePostQuery = new Queries\Delete\DeletePostQuery(TEST_USER_ID, $this->resourceHash);

        // Check status code.
        $statusCode = $deletePostQuery->execute($ACCESSOR_BASICAUTH->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($deletePostQuery->isExecuted());
    }

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor)
    {

        $this->createPostQuery = new CreatePostQuery($this->post, TEST_USER_ID);

        // Check status code.
        $statusCode = $this->createPostQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('201', $statusCode);

        // Save resource-hash.
        $this->resourceHash = $this->createPostQuery->getResourceHash();

        // Is executed flag set?
        $this->assertTrue($this->createPostQuery->isExecuted());

        // Check if the post was created in the expected way.
        $getPostQuery = new Queries\Get\GetPostsQuery(
            Config\Resourcetype::BOOKMARK,
            Config\Grouping::USER,
            TEST_USER_ID,
            [$this->tagName]
        );

        $statusCodeGet = $getPostQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCodeGet);

        // TODO: Check tags.

        // Check type.
        $posts = $getPostQuery->model();
        foreach ($posts as $post) {
            $this->assertTrue($post instanceof Model\Post);
        }
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }
}

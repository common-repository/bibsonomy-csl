<?php

namespace AcademicPuma\RestClient\Queries\Delete;

use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Queries;
use PHPUnit\Framework\TestCase;


class DeletePostQueryTest extends TestCase
{

    /**
     * @var DeletePostQuery
     */
    protected $deletePostQuery;

    /**
     *
     * @var string
     */
    private $resourceHash;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {

        global $ACCESSOR_BASICAUTH;

        // At first a post has to be created in order to test the delete action afterwards.

        // Init user.
        $user = new Model\User();
        $user->setName(TEST_USER_ID);

        // Init tag.
        $tag = new Model\Tag();
        $tag->setName('test-delete-post');

        // Init group.
        $group = new Model\Group();
        $group->setName('private');

        // Init bookmark.
        $bookmark = new Model\Bookmark();
        $bookmark->setTitle('RestClient Repository');
        $bookmark->setUrl('https://bitbucket.org/bibsonomy/restclient-php');

        // Init posts.
        $post = new Model\Post();
        $post->setUser($user);
        $post->addTag($tag);
        $post->setDescription('Test description.');
        $post->setResource($bookmark);
        $post->setGroup($group);

        // Create the post.
        $createPostQuery = new Queries\Post\CreatePostQuery($post, TEST_USER_ID);
        $this->resourceHash = $createPostQuery->execute($ACCESSOR_BASICAUTH->getClient())->getResourceHash();
        // $this->resourceHash = '914a9786cb46d8426e86ab2af1239dd8';
    }

    /**
     * @covers       AcademicPuma\RestClient\Queries\Delete\DeletePostQuery::execute
     * @dataProvider executeProvider
     */
    public function testExecute($accessor)
    {

        $this->deletePostQuery = new DeletePostQuery(TEST_USER_ID, $this->resourceHash);

        // Check status code.
        $statusCode = $this->deletePostQuery->execute($accessor->getClient())->getStatusCode();
        $this->assertEquals('200', $statusCode);

        // Is executed flag set?
        $this->assertTrue($this->deletePostQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH)
        );
    }
}

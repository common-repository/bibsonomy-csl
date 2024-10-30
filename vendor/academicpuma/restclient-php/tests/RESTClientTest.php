<?php

/**
 *
 *  Copyright (C) 2006 - 2014 Knowledge & Data Engineering Group,
 *                            University of Kassel, Germany
 *                            http://www.kde.cs.uni-kassel.de/
 *
 *  This program is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public License
 *  as published by the Free Software Foundation; either version 2
 *  of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace AcademicPuma\RestClient;

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;
use AcademicPuma\RestClient\Authentication\OAuth\Token\AccessToken;
use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use PHPUnit\Framework\TestCase;


/**
 * Tests the RESTClient class.
 *
 * @author Florian Fassing
 */
class RESTClientTest extends TestCase
{
    private $basicClient;

    private $oauthClient;

    /**
     * Create one client for all test methods.
     */
    protected function setUp(): void
    {
        // Create http client with basic auth for testing.
        $basicAuthAccessor = new BasicAuthAccessor(BIBSONOMY_HOST_URL, TEST_USER_ID, API_KEY);
        $this->basicClient = new RESTClient($basicAuthAccessor);

        // Create access token and http client with oauth for testing.
        $accessToken = new AccessToken();
        $accessToken->setOauthToken(OAUTH_ACCESS_TOKEN);
        $accessToken->setOauthTokenSecret(OAUTH_ACCESS_TOKEN_SEC);
    }

    public function getConceptDetailsProvider(): array
    {
        return [
            ['_foo', TEST_USER_ID]
        ];
    }

    /**
     * @dataProvider getConceptDetailsProvider
     */
    public function testGetConceptDetails($conceptName, $userName)
    {
        $query = $this->basicClient->getConceptDetails($conceptName, $userName)->getQuery();
        $this->assertEquals('200', $query->getStatusCode());
    }

    public function getConceptsProvider(): array
    {
        return [
            ['_foo', TEST_USER_ID]
        ];
    }

    /**
     * @dataProvider getConceptsProvider
     */
    public function testGetConcepts($conceptName, $userName)
    {

        /** @var Model\Tag $concept */
        $concept = $this->basicClient->getConceptDetails($conceptName, $userName)->model();
        $this->assertNotEmpty($concept);
        $this->assertEquals(200, $this->basicClient->getQuery()->getStatusCode());
        $this->assertContains('_subfoo', $concept->getSubTags());
    }


    public function getDocumentFileProvider(): array
    {
        return array(
            array(TEST_USER_ID, 'c49c9ef4dc754980aa36760133790f69', 'Vergleich_Sortierverfahren.pdf',
                Config\DocumentType::FILE)
        );
    }

    /**
     * @dataProvider getDocumentFileProvider
     *
     * @param $userName
     * @param $resourceHash
     * @param $fileName
     * @param $type
     *
     * @throws UnsupportedOperationException
     */
    public function testGetDocumentFile($userName, $resourceHash, $fileName, $type)
    {

        $query = $this->basicClient->getDocumentFile($userName, $resourceHash, $fileName, $type)->getQuery();
        $statusCode = $query->getStatusCode();
        $this->basicClient->file()->getContents();
        $this->assertEquals('200', $statusCode);
    }

    /**
     * Data Provider for getGroupsProvider
     * @return array
     */
    public function getGroupsProvider(): array
    {
        return array(
            array(0, 5, 5),
            array(1, 5, 4),
            array(3, 5, 2)
        );
    }

    /**
     * @dataProvider getGroupsProvider
     */
    public function testGetGroups($start, $end, $expectedSize)
    {

        $this->basicClient->getGroups($start, $end);

        /** @var Model\Groups $groups */
        $groups = $this->basicClient->model();
        $this->assertTrue($groups->count() === $expectedSize);
    }


    /* GET POST DETAILS */

    public function getPostDetailsProvider(): array
    {
        return [
            [TEST_USER_ID, TEST_RESOURCE_HASH]
        ];
    }

    /**
     * @dataProvider getPostDetailsProvider
     */
    public function testGetPostDetails($userName, $resourceHash)
    {

        /** @var \AcademicPuma\RestClient\Model\Post $post */
        $post = $this->basicClient->getPostDetails($userName, $resourceHash)->model();
        $this->assertNotEmpty($post->getResource());

    }

    /* GET POSTS */

    public function getPostsProvider(): array
    {
        return [
            [Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, ['test'], '', '', [], [], 0, 0, 'local'],
            [Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, [], '271dd1a473eaf0af9840758653746c221', '', [], [], 0, 0, 'local'],
            [Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, [], '', '', [], [], 0, 2, 'local'],
            [Resourcetype::BOOKMARK, Grouping::USER, TEST_USER_ID, [], '', '', [], [], 0, 0, 'local'],
            [Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, ['test'], '', '', [], [], 0, 0, 'searchindex'],
            [Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, [], '271dd1a473eaf0af9840758653746c221', '', [], [], 0, 0, 'searchindex'],
            [Resourcetype::BIBTEX, Grouping::USER, TEST_USER_ID, [], '', '', [], [], 0, 2, 'searchindex'],
            [Resourcetype::BOOKMARK, Grouping::USER, TEST_USER_ID, [], '', '', [], [], 0, 0, 'searchindex']
        ];
    }

    /**
     * @dataProvider getPostsProvider
     * @throws UnsupportedOperationException
     */
    public function testGetPosts($resourceType, $grouping, $groupingName, $tags, $resourceHash, $search, $sortKeys, $sortOrders, $start, $end, $searchType)
    {

        $this->basicClient->getPosts($resourceType, $grouping, $groupingName, $tags, $resourceHash, $search, $sortKeys, $sortOrders, $start, $end, 'xml', $searchType);
        /** @var \AcademicPuma\RestClient\Model\Posts $posts */
        $posts = $this->basicClient->model();

        $this->assertInstanceOf('\\AcademicPuma\\RestClient\\Model\\Posts', $posts);
        $this->assertNotEmpty($posts->toArray());
        foreach ($posts as $post) {
            $this->assertInstanceOf('\\AcademicPuma\\RestClient\\Model\\Post', $post);
            if ($resourceHash === '271dd1a473eaf0af9840758653746c221') {
                $title = 'The Impact of Resource Title on Tags in Collaborative Tagging Systems';
                /** @var \AcademicPuma\RestClient\Model\Post $post */
                $this->assertTrue($post->getResource()->getTitle() === $title);
            }
        }

    }

    /* GET TAG DETAILS */

    public function getTagDetailsProvider(): array
    {
        return array(
            array('myown')
        );
    }

    /**
     * @dataProvider getTagDetailsProvider
     */
    public function testGetTagDetails($tagName)
    {
        /** @var Model\Tag $tag */
        $tag = $this->basicClient->getTagDetails($tagName)->model();
        $this->assertEquals('myown', $tag->getName());
    }


    /* GET TAGS */

    public function getTagsProvider(): array
    {
        return [
            [Grouping::USER, TEST_USER_ID, 'alph', 0, 20]
        ];
    }

    /**
     * @dataProvider getTagsProvider
     */
    public function testGetTags($grouping, $groupingName, $order, $start, $end)
    {
        /** @var Model\Tags $tags */
        $tags = $this->basicClient->getTags($grouping, $groupingName, '', $order, $start, $end)->model();
        $this->assertTrue($tags->count() > 0);
    }


    public function testGetTagRelation()
    {
        $this->basicClient->getTagRelation(Config\Grouping::USER, TEST_USER_ID, Config\TagRelation::RELATED, ['puma'], Config\TagOrder::FREQUENCY, 0, 20);
        $tags = $this->basicClient->model();
        $this->assertNotNull($tags);
        $this->assertTrue($tags->count() > 0);
    }

    public function getUserDetailsProvider(): array
    {
        return [[TEST_USER_ID]];
    }

    /**
     * @dataProvider getUserDetailsProvider
     */
    public function testGetUserDetails($userName)
    {
        $this->basicClient->getUserDetails($userName);
        /** @var Model\User $user */
        $user = $this->basicClient->model();
        $this->assertNotEmpty($user->getName());
    }

    /**
     *
     */
    public function testGetUserRelationship()
    {
        //Tested in Query
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testGetUsers()
    {
        /** @var Model\Users $users */
        $users = $this->basicClient->getUsers(0, 20)->model();
        $this->assertInstanceOf('\\AcademicPuma\\RestClient\\Model\\Users', $users);
        $this->assertNotEmpty($users->toArray());
    }

    /**
     *
     */
    public function testUpdateConcept()
    {
        //Tested in Query
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testUpdateGroup()
    {
        //Tested in Query
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testUpdatePosts()
    {
        //Tested in Query
        $this->assertTrue(true);
    }

    public function bibliographyProvider(): array
    {
        return [
            ['user', TEST_USER_ID, ['test'], 0, 100, 'apa', 'en-US', 'local'],
            ['user', TEST_USER_ID, ['test'], 0, 100, 'apa', 'en-US', 'searchindex']
        ];
    }

    /**
     * @dataProvider bibliographyProvider
     *
     */
    public function testBibliography($grouping, $groupingName, $tags, $start, $end, $style, $lang, $searchType)
    {

        $bib = $this->basicClient->getPosts(Config\Resourcetype::BIBTEX, $grouping, $groupingName, $tags, '', '', [], [], $start, $end, 'xml', $searchType)
            ->bibliography($style, $lang, false);
        $this->assertTrue(is_string($bib));
        $this->assertStringStartsWith('<div class="csl-bib-body">', $bib);
    }

    public function bibtexProvider(): array
    {
        return [
            ['user', TEST_USER_ID, 0, 100, 'local'],
            ['user', TEST_USER_ID, 0, 100, 'searchindex']
        ];
    }

    /**
     * @dataProvider bibtexProvider
     *
     */
    public function testBibtex($grouping, $groupingName, $start, $end, $searchType)
    {
        $bibtex = $this->basicClient
            ->getPosts(Config\Resourcetype::BIBTEX, $grouping, $groupingName, [], '', '', [], [], $start, $end, 'xml', $searchType)
            ->bibtex();
        $this->assertTrue(is_string($bibtex));
        $this->assertStringStartsWith('@', $bibtex);

        print_r($bibtex);
    }
}

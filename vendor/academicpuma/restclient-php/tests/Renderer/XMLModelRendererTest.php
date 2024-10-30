<?php

namespace AcademicPuma\RestClient\Renderer;

use AcademicPuma\RestClient\Model\Bibtex;
use AcademicPuma\RestClient\Model\Bookmark;
use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Model\Documents;
use AcademicPuma\RestClient\Model\Group;
use AcademicPuma\RestClient\Model\Groups;
use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Model\Persons;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Project;
use AcademicPuma\RestClient\Model\Projects;
use AcademicPuma\RestClient\Model\ResourceLink;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Model\ResourcePersonRelations;
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Model\Tags;
use AcademicPuma\RestClient\Model\User;
use AcademicPuma\RestClient\Model\Users;
use DOMException;
use PHPUnit\Framework\TestCase;


class XMLModelRendererTest extends TestCase
{
    protected function setUp(): void
    {
        $this->xmlRenderer = new XMLModelRenderer();
    }

    /**
     * @throws DOMException
     */
    public function testSerializeResource()
    {
        ####### test bookmark #######
        $bookmark = new Bookmark();
        $bookmark
            ->setTitle('Title')
            ->setUrl('http://www.host.tld');
        $bookmarkXML = TestHelper::loadXML('bookmark.xml');

        $actualBookmark = $this->xmlRenderer->render($bookmark);
        $this->assertEquals($bookmarkXML, $actualBookmark);

        ####### test bibtex #######
        $bibtex = new Bibtex();
        $bibtex
            ->setInterhash('fe8d3f1d9a0c7f0b0f4c4ae156a9b00d')
            ->setIntrahash('4c4916e0eab92e3e6465711a2f978885')
            ->setEntrytype('presentation')
            ->setBibtexKey('boettger2014puma')
            ->setTitle('PUMA als Schnittstelle zwischen Discovery Service, Institutional Repository und eLearning')
            ->setBibtexAbstract('bla')
            ->setAddress('Reutlingen')
            ->setAuthor('Böttger, Sebastian')
            ->setMonth('07')
            ->setPublisher('Berufsverband Information Bibliothek')
            ->setYear('2014')
            ->setUrl('http://www.opus-bayern.de/bib-info/volltexte//2014/1674');
        $bibtexXML = TestHelper::loadXML('bibtex.xml');

        $actualBibtex = $this->xmlRenderer->render($bibtex);
        $this->assertEquals($bibtexXML, $actualBibtex);
    }

    /**
     * @throws DOMException
     */
    public function testSerializePost()
    {
        $user = new User();
        $user
            ->setName('a');

        $tag0 = new Tag();
        $tag0->setName('Tag0');
        $tag1 = new Tag();
        $tag1->setName('Tag1');

        $bookmark = new Bookmark();
        $bookmark
            ->setTitle('Title')
            ->setUrl('http://www.host.tld');

        $post = new Post();
        $post
            ->setDescription('a description')
            ->setUser($user)
            ->setTag(new Tags([$tag0, $tag1]))
            ->setResource($bookmark);

        $postXML = TestHelper::loadXML('post.xml');

        $actual = $this->xmlRenderer->render($post);
        $this->assertEquals($postXML, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializePosts()
    {
        $postsXML = TestHelper::loadXML('posts.xml');

        //print_r($postsObject);
        $actual = $this->xmlRenderer->render(null);
        $this->assertEquals($postsXML, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeUser()
    {
        // Create user
        $user = new User();
        $user
            ->setName("mustermann")
            ->setRealname("Mustermann, Max")
            ->setEmail("mail@host.tld")
            ->setHomepage("http://host.tld")
            ->setHref("http://host.tld/api/users/mustermann");

        // Create groups for user
        $group = new Group();
        $group
            ->setName("group1")
            ->setHref("http://puma.uni-kassel.de/api/groups/group1");
        $userGroups = new Groups([$group]);
        $userGroups->setStart(0);
        $userGroups->setEnd(1);
        $user->setGroups($userGroups);

        $actual = $this->xmlRenderer->render($user);

        $userXML = TestHelper::loadXML('user.xml');
        $this->assertEquals($userXML, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeUsers()
    {
        $user = new User();
        $user->setName('mustermann');
        $user->setRealname('Max Mustermann');
        $user->setEmail('max.mustermann@host.tld');

        $users = new Users();
        $users[0] = $user;
        $users[1] = $user;

        $expected = '<bibsonomy><users><user name="mustermann" realname="Max Mustermann" email="max.mustermann@host.tld"/><user name="mustermann" realname="Max Mustermann" email="max.mustermann@host.tld"/></users></bibsonomy>';
        $actual = $this->xmlRenderer->render($users);

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeTags()
    {
        $postsXML = TestHelper::loadXML('tags.xml');

        $actual = $this->xmlRenderer->render(null);
        $this->assertEquals($postsXML, $actual);


        // Concepts
        $superTag = new Tag();
        $superTag->setName('_supertag');

        $subTag = new Tag();
        $subTag->setName('_subtag');

        $superTag->setSubTags(new Tags([$subTag]));

        $tags = new Tags([$superTag]);

        $actualXml = $this->xmlRenderer->render($tags);

        $expectedXml = '
<bibsonomy>
    <tags>
        <tag name="_supertag">
            <subTags>
                <tag name="_subtag"/>
            </subTags>
        </tag>
    </tags>
</bibsonomy>';

        $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);
    }


    /**
     * @throws DOMException
     */
    public function testSerializeGroups()
    {

        $group = new Group();
        $group
            ->setName("seminar")
            ->setHref("http://host.tld/api/groups/seminar");

        $groups = new Groups();

        $groups[0] = $group;
        $groups[1] = $group;

        $actual = $this->xmlRenderer->render($groups);
        $expected = '<bibsonomy><groups><group name="seminar" href="http://host.tld/api/groups/seminar"/><group name="seminar" href="http://host.tld/api/groups/seminar"/></groups></bibsonomy>';
        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeDocuments()
    {
        // Create document.
        $document = new Document();
        $document->setFilename('foo.bar');
        $document->setUserName(TEST_USER_ID);

        $documents = new Documents();
        $documents[0] = $document;
        $documents[1] = $document;

        $actual = $this->xmlRenderer->render($documents);
        $expected = '<bibsonomy><documents><document filename="foo.bar"/><document filename="foo.bar"/></documents></bibsonomy>';

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializePerson()
    {
        // Create person
        $person = new Person();
        $person->setMainName('Max Mustermann');
        $person->setPersonId('m.mustermann');
        $person->setUser('mmustermann');
        $person->setHomepage('http://www.host.tld');

        $actual = $this->xmlRenderer->render($person);
        $expected = TestHelper::loadXML('person.xml');

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializePersons()
    {
        // Create persons
        $persons = new Persons();
        $persons->setStart(0);
        $persons->setEnd(1);
        $persons->setNext('http://host.tld/api/persons?start=1&end=2');

        // Create person
        $person = new Person();
        $person->setMainName('Max Mustermann');
        $person->setPersonId('m.mustermann');
        $person->setUser('mmustermann');
        $person->setHomepage('http://www.host.tld');
        $persons->add(0, $person);

        $actual = $this->xmlRenderer->render($persons);
        $expected = TestHelper::loadXML('persons.xml');

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializePersonResourceRelation()
    {
        // Create resource person relation
        $relation = new ResourcePersonRelation();
        $relation->setRelationType('author');
        $relation->setPersonIndex(0);
        $resourceLink = new ResourceLink();
        $resourceLink->setInterHash('fe8d3f1d9a0c7f0b0f4c4ae156a9b00d');
        $resourceLink->setIntraHash('4c4916e0eab92e3e6465711a2f978885');
        $relation->setResourceLink($resourceLink);
        $person = new Person();
        $person->setMainName('Max Mustermann');
        $person->setPersonId('m.mustermann');
        $person->setUser('mmustermann');
        $person->setHomepage('http://www.host.tld');
        $relation->setPerson($person);

        $actual = $this->xmlRenderer->render($relation);
        $expected = TestHelper::loadXML('resource-person-relation.xml');

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializePersonResourceRelations()
    {
        // Create resource person relations
        $relations = new ResourcePersonRelations();

        // Create resource person relation
        $relation = new ResourcePersonRelation();
        $relation->setRelationType('author');
        $relation->setPersonIndex(0);
        $resourceLink = new ResourceLink();
        $resourceLink->setInterHash('fe8d3f1d9a0c7f0b0f4c4ae156a9b00d');
        $resourceLink->setIntraHash('4c4916e0eab92e3e6465711a2f978885');
        $relation->setResourceLink($resourceLink);
        $person = new Person();
        $person->setMainName('Max Mustermann');
        $person->setPersonId('m.mustermann');
        $person->setUser('mmustermann');
        $person->setHomepage('http://www.host.tld');
        $relation->setPerson($person);
        $relations->add(0, $relation);

        $actual = $this->xmlRenderer->render($relations);
        $expected = TestHelper::loadXML('resource-person-relations.xml');

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeResourceLink()
    {
        // create resourceLink
        $resourceLink = new ResourceLink();
        $resourceLink->setInterHash('fe8d3f1d9a0c7f0b0f4c4ae156a9b00d');
        $resourceLink->setIntraHash('4c4916e0eab92e3e6465711a2f978885');

        $actual = $this->xmlRenderer->render($resourceLink);
        $expected = '<bibsonomy><resource interhash="fe8d3f1d9a0c7f0b0f4c4ae156a9b00d" intrahash="4c4916e0eab92e3e6465711a2f978885"/></bibsonomy>';

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeProject()
    {
        // create project
        $project = new Project();
        $project->setExternalId('puma');
        $project->setTitle('PUMA');
        $project->setSubTitle('Publication Management');
        $project->setDescription('A publication management system');
        $project->setType('');
        $project->setBudget(10000.0);
        $project->setStartDate('2013-02-01T00:00:00.000+01:00');
        $project->setEndDate('2016-08-01T00:00:00.000+02:00');
        $project->setSponsor('Unknown');

        $actual = $this->xmlRenderer->render($project);
        $expected = TestHelper::loadXML('project.xml');

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @throws DOMException
     */
    public function testSerializeProjects()
    {
        // Create projects
        $projects = new Projects();

        // create project
        $project = new Project();
        $project->setExternalId('puma');
        $project->setTitle('PUMA');
        $project->setSubTitle('Publication Management');
        $project->setDescription('A publication management system');
        $project->setType('');
        $project->setBudget(10000.0);
        $project->setStartDate('2013-02-01T00:00:00.000+01:00');
        $project->setEndDate('2016-08-01T00:00:00.000+02:00');
        $project->setSponsor('Unknown');
        $projects->add(0, $project);

        $actual = $this->xmlRenderer->render($projects);
        $expected = TestHelper::loadXML('projects.xml');

        $this->assertXmlStringEqualsXmlString($expected, $actual);
    }

}
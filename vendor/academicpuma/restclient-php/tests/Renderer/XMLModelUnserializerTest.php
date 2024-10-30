<?php

namespace AcademicPuma\RestClient\Renderer;

use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Model\Persons;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Model\Project;
use AcademicPuma\RestClient\Model\Projects;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Model\ResourcePersonRelations;
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Model\Tags;
use AcademicPuma\RestClient\Model\User;
use AcademicPuma\RestClient\Model\Users;
use AcademicPuma\RestClient\Util\Collection\ArrayList;
use DOMException;
use PHPUnit\Framework\TestCase;
use ReflectionException;


class XMLModelUnserializerTest extends TestCase
{
    /**
     * @throws DOMException
     * @throws ReflectionException
     */
    public function testConvertToModel()
    {
        ####### TEST post #######
        $xmlString1 = TestHelper::loadXML('post.xml');
        $post = (new XMLModelUnserializer($xmlString1))->convertToModel();
        $this->assertTrue($post instanceof Post);

        ####### TEST posts #######
        $xmlString2 = TestHelper::loadXML('posts.xml');
        $posts = (new XMLModelUnserializer($xmlString2))->convertToModel();

        $this->assertTrue($posts instanceof Posts);

        foreach ($posts as $post) {
            $this->assertTrue($post instanceof Post);

            $this->assertNotEmpty($post->getUser()->getName());
            $this->assertNotEmpty($post->getTag());
        }

        ####### TEST group #######
        $xmlString3 = TestHelper::loadXML('group.xml');
        $group = (new XMLModelUnserializer($xmlString3))->convertToModel();

        $this->assertEquals($group->getName(), 'seminar');

        ####### TEST tags #######
        $xmlString4 = TestHelper::loadXML('tags.xml');
        $tags = (new XMLModelUnserializer($xmlString4))->convertToModel();

        $this->assertTrue($tags instanceof Tags);
        $this->assertTrue($tags instanceof ArrayList);
        $this->assertNotEmpty($tags);
        foreach ($tags as $tag) {
            $this->assertTrue($tag instanceof Tag);
            $this->assertNotEmpty($tag->getName());
        }

        ####### TEST user #######
        $xmlString5 = TestHelper::loadXML('user.xml');
        $user = (new XMLModelUnserializer($xmlString5))->convertToModel();

        $this->assertEquals($user->getName(), 'mustermann');

        ####### TEST users #######
        $xmlString6 = TestHelper::loadXML('users.xml');
        $users = (new XMLModelUnserializer($xmlString6))->convertToModel();

        $this->assertTrue($users instanceof Users);
        $this->assertTrue($users instanceof ArrayList);
        $this->assertNotEmpty($users);
        foreach ($users as $user) {
            $this->assertTrue($user instanceof User);
            $this->assertNotEmpty($user->getName());
        }

        ###### TEST person ######
        $xmlString7 = TestHelper::loadXML('person.xml');
        $person = (new XMLModelUnserializer($xmlString7))->convertToModel();

        $this->assertEquals($person->getMainName(), 'Max Mustermann');

        ###### TEST persons ######
        $xmlString8 = TestHelper::loadXML('persons.xml');
        $persons = (new XMLModelUnserializer($xmlString8))->convertToModel();

        $this->assertTrue($persons instanceof Persons);
        $this->assertTrue($persons instanceof ArrayList);
        $this->assertNotEmpty($persons);
        foreach ($persons as $person) {
            $this->assertTrue($person instanceof Person);
            $this->assertNotEmpty($person->getMainName());
        }

        ###### TEST resource person relation ######
        $xmlString9 = TestHelper::loadXML('resource-person-relation.xml');
        $resourcePersonRelation = (new XMLModelUnserializer($xmlString9))->convertToModel();

        $this->assertEquals($resourcePersonRelation->getRelationType(), 'author');

        ###### TEST resource person relations ######
        $xmlString10 = TestHelper::loadXML('resource-person-relations.xml');
        $resourcePersonRelations = (new XMLModelUnserializer($xmlString10))->convertToModel();

        $this->assertTrue($resourcePersonRelations instanceof ResourcePersonRelations);
        $this->assertTrue($resourcePersonRelations instanceof ArrayList);
        $this->assertNotEmpty($resourcePersonRelations);
        foreach ($resourcePersonRelations as $resourcePersonRelation) {
            $this->assertTrue($resourcePersonRelation instanceof ResourcePersonRelation);
            $this->assertNotEmpty($resourcePersonRelation->getRelationType());
        }

        ##### TEST project #####
        $xmlString10 = TestHelper::loadXML('project.xml');
        $project = (new XMLModelUnserializer($xmlString10))->convertToModel();
        $this->assertTrue($project instanceof Project);
        $this->assertEquals($project->getTitle(), 'PUMA');
        $this->assertEquals($project->getDescription(), 'A publication management system');

        ##### TEST projects #####
        $xmlString11 = TestHelper::loadXML('projects.xml');
        $projects = (new XMLModelUnserializer($xmlString11))->convertToModel();

        $this->assertTrue($projects instanceof Projects);
        $this->assertTrue($projects instanceof ArrayList);
        $this->assertNotEmpty($projects);
        foreach ($projects as $project) {
            $this->assertTrue($project instanceof Project);
            $this->assertNotEmpty($project->getExternalId());
        }
    }
}

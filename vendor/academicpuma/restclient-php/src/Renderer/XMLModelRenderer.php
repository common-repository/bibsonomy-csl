<?php

/*
 *  restclient-php is a full-featured REST client for PUMA and/or
 *  BibSonomy.
 *
 *  Copyright (C) 2015
 *
 *  Knowledge & Data Engineering Group,
 *  University of Kassel, Germany
 *  http://www.kde.cs.uni-kassel.de/
 *
 *  HothoData GmbH, Germany
 *  http://www.academic-puma.de
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AcademicPuma\RestClient\Renderer;

use AcademicPuma\RestClient\Model;
use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Model\Documents;
use AcademicPuma\RestClient\Model\Exceptions\InvalidModelObjectException;
use AcademicPuma\RestClient\Model\Group;
use AcademicPuma\RestClient\Model\Groups;
use AcademicPuma\RestClient\Model\ModelObject;
use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Model\Persons;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Model\Projects;
use AcademicPuma\RestClient\Model\Resource;
use AcademicPuma\RestClient\Model\ResourceLink;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Model\ResourcePersonRelations;
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Model\Tags;
use AcademicPuma\RestClient\Model\User;
use AcademicPuma\RestClient\Model\Users;
use DOMDocument;
use DOMElement;
use DOMException;
use DOMNode;
use ReflectionClass;
use ReflectionException;

/**
 * Converts model object structure into XML string
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class XMLModelRenderer extends ModelRenderer
{

    private static $DOM_POSTS_ATTRIBUTES = ['start', 'end', 'next'];

    private static $DOM_TAGS_ATTRIBUTES = ['start', 'end'];

    private static $DOM_USERS_ATTRIBUTES = ['start', 'end'];

    private static $DOM_GROUPS_ATTRIBUTES = ['start', 'end'];

    private static $DOM_DOCUMENTS_ATTRIBUTES = ['start', 'end'];

    private static $DOM_PERSONS_ATTRIBUTES = ['start', 'end', 'next'];

    private static $DOM_RESOURCE_PERSON_RELATIONS_ATTRIBUTES = [];

    private static $DOM_PROJECTS_ATTRIBUTES = [];

    private static $DOM_POST_ATTRIBUTES = ['description', 'postingdate', 'changedate'];

    private static $DOM_USER_ATTRIBUTES = ['name', 'realname', 'email', 'homepage', 'href'];

    private static $DOM_TAG_ATTRIBUTES = ['name', 'globalcount', 'usercount', 'href'];

    private static $DOM_GROUP_ATTRIBUTES = ['name', 'description', 'href'];

    private static $DOM_RESOURCE_ATTRIBUTES = ['interhash', 'intrahash'];

    private static $DOM_BOOKMARK_ATTRIBUTES = ['title', 'url', 'href'];

    private static $DOM_DOCUMENT_ATTRIBUTES = ['filename', 'md5hash', 'href'];

    private static $DOM_PERSON_ATTRIBUTES = ['names', 'personId', 'gender', 'homepage', 'email',
        'college', 'academicdegree', 'orcid', 'researcherid'];

    private static $DOM_RESOURCE_PERSON_RELATION_ATTRIBUTES = [];

    private static $DOM_PROJECT_ATTRIBUTES = ['externalId', 'title', 'subTitle', 'description', 'type',
        'startDate', 'endDate', 'sponsor', 'budget'];

    private static $DOM_BIBTEX_ATTRIBUTES = [
        'title',
        'bibtexKey',
        'bKey',
        'misc',
        'bibtexAbstract',
        'entrytype',
        'address',
        'annote',
        'author',
        'booktitle',
        'chapter',
        'crossref',
        'edition',
        'editor',
        'howpublished',
        'institution',
        'organization',
        'journal',
        'note',
        'number',
        'pages',
        'publisher',
        'school',
        'series',
        'volume',
        'day',
        'month',
        'year',
        'type',
        'url',
        'privnote',
        'href'
    ];

    const CLASS_TAGS = '\\AcademicPuma\\RestClient\\Model\\Tags';

    const CLASS_POSTS = '\\AcademicPuma\\RestClient\\Model\\Posts';

    const CLASS_USERS = '\\AcademicPuma\\RestClient\\Model\\Users';

    const CLASS_GROUPS = '\\AcademicPuma\\RestClient\\Model\\Groups';

    const CLASS_DOCUMENTS = '\\AcademicPuma\\RestClient\\Model\\Documents';

    const CLASS_PERSONS = '\\AcademicPuma\\RestClient\\Model\\Persons';

    const CLASS_RESOURCE_PERSON_RELATIONS = '\\AcademicPuma\\RestClient\\Model\\ResourcePersonRelations';

    const CLASS_PROJECTS = '\\AcademicPuma\\RestClient\\Model\\Projects';

    const CLASS_RESOURCE = '\\AcademicPuma\\RestClient\\Model\\Resource';

    const CLASS_BOOKMARK = '\\AcademicPuma\\RestClient\\Model\\Bookmark';

    const CLASS_BIBTEX = '\\AcademicPuma\\RestClient\\Model\\Bibtex';

    const CLASS_GOLD_STANDARD_BOOKMARK = '\\AcademicPuma\\RestClient\\Model\\GoldStandardBookmark';

    const CLASS_GOLD_STANDARD_BIBTEX = '\\AcademicPuma\\RestClient\\Model\\GoldStandardPublication';

    const CLASS_TAG = '\\AcademicPuma\\RestClient\\Model\\Tag';

    const CLASS_POST = '\\AcademicPuma\\RestClient\\Model\\Post';

    const CLASS_USER = '\\AcademicPuma\\RestClient\\Model\\User';

    const CLASS_GROUP = '\\AcademicPuma\\RestClient\\Model\\Group';

    const CLASS_DOCUMENT = '\\AcademicPuma\\RestClient\\Model\\Document';

    const CLASS_PERSON = '\\AcademicPuma\\RestClient\\Model\\Person';

    const CLASS_RESOURCE_PERSON_RELATION = '\\AcademicPuma\\RestClient\\Model\\ResourcePersonRelation';

    const CLASS_RESOURCE_LINK = '\\AcademicPuma\\RestClient\\Model\\ResourceLink';

    const CLASS_PROJECT = '\\AcademicPuma\\RestClient\\Model\\Project';

    private $doc;

    public function __construct()
    {
        $this->doc = new DOMDocument();
    }

    /**
     * @param Resource $resource
     * @return DOMElement
     * @throws DOMException
     */
    function serializeResource(Resource $resource): DOMElement
    {
        $resourceNode = null;

        if ($resource instanceof Model\Bookmark) {
            $resNode = $this->doc->createElement('bookmark');
            $resourceNode = $this->doc->appendChild($resNode);
            $this->appendAttributeNodes($resourceNode, self::CLASS_RESOURCE, self::$DOM_RESOURCE_ATTRIBUTES, $resource);
            $this->appendAttributeNodes($resourceNode, self::CLASS_BOOKMARK, self::$DOM_BOOKMARK_ATTRIBUTES, $resource);
        }
        if ($resource instanceof Model\Bibtex) {
            $resNode = $this->doc->createElement('bibtex');
            $resourceNode = $this->doc->appendChild($resNode);
            $this->appendAttributeNodes($resourceNode, self::CLASS_RESOURCE, self::$DOM_RESOURCE_ATTRIBUTES, $resource);
            $this->appendAttributeNodes($resourceNode, self::CLASS_BIBTEX, self::$DOM_BIBTEX_ATTRIBUTES, $resource);
        }
        if ($resource instanceof Model\GoldStandardBookmark) {
            $resNode = $this->doc->createElement('goldStandardBookmark');
            $resourceNode = $this->doc->appendChild($resNode);
        }
        if ($resource instanceof Model\GoldStandardPublication) {
            $resNode = $this->doc->createElement('goldStandardPublication');
            $resourceNode = $this->doc->appendChild($resNode);
        }
        return $resourceNode;
    }

    /**
     * @param Post $post
     *
     * @return DOMElement
     * @throws InvalidModelObjectException
     * @throws DOMException
     */
    function serializePost(Post $post): DOMElement
    {

        if ($post->getUser() === null || $post->getTag() === null || $post->getResource() === null) {
            throw new InvalidModelObjectException('Resource, tags or user missing');
        }

        $postNode = $this->doc->createElement('post');

        if (!empty($post->getGroup())) {
            $postNode->appendChild(
                $this->serializeGroup($post->getGroup())
            );
        }

        $postNode->appendChild(
            $this->serializeUser($post->getUser())
        );

        foreach ($post->getTag() as $tag) {
            $postNode->appendChild(
                $this->serializeTag($tag)
            );
        }

        $postNode->appendChild(
            $this->serializeResource($post->getResource())
        );

        $this->appendAttributeNodes(
            $postNode,
            self::CLASS_POST,
            self::$DOM_POST_ATTRIBUTES,
            $post);

        return $postNode;
    }

    /**
     * @param Posts $posts
     *
     * @return DOMElement
     * @throws InvalidModelObjectException
     * @throws DOMException
     */
    function serializePosts(Posts $posts): DOMElement
    {

        $postsNode = $this->doc->createElement('posts');

        foreach ($posts as $post) {
            $postsNode->appendChild(
                $this->serializePost($post)
            );
        }

        $this->appendAttributeNodes(
            $postsNode,
            self::CLASS_POSTS,
            self::$DOM_POSTS_ATTRIBUTES,
            $posts);

        return $postsNode;
    }

    /**
     * @param Tag $tag
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeTag(Tag $tag): DOMElement
    {

        $tagNode = $this->doc->createElement('tag');

        $newNode = $this->doc->appendChild($tagNode);

        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_TAG,
            self::$DOM_TAG_ATTRIBUTES,
            $tag);

        if (!$tag->getSubTags()->isEmpty()) {
            $subTags = $this->doc->createElement('subTags');

            foreach ($tag->getSubTags() as $subTag) {
                //append each subTag to subTags-node
                $subTags->appendChild($this->serializeTag($subTag)); //recursive
            }
            $tagNode->appendChild($subTags); //append subTags-node to the tag node
        }

        return $newNode;
    }

    /**
     * @param Tags $tags
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeTags(Tags $tags): DOMElement
    {

        $tagsNode = $this->doc->createElement('tags');

        foreach ($tags as $tag) {
            $tagsNode->appendChild(
                $this->serializeTag($tag)
            );
        }

        $this->appendAttributeNodes(
            $tagsNode,
            self::CLASS_TAGS,
            self::$DOM_TAGS_ATTRIBUTES,
            $tags);

        return $tagsNode;
    }

    /**
     *
     * @param User $user
     * @return DOMElement
     * @throws DOMException
     */
    function serializeUser(User $user): DOMElement
    {

        $userNode = $this->doc->createElement('user');

        $newNode = $this->doc->appendChild($userNode);

        if (!$user->getGroups()->isEmpty()) {
            $newNode->appendChild(
                $this->serializeGroups($user->getGroups())
            );
        }
        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_USER,
            self::$DOM_USER_ATTRIBUTES,
            $user);

        return $newNode;
    }

    /**
     * @param Users $users
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeUsers(Users $users): DOMElement
    {
        $usersNode = $this->doc->createElement('users');

        foreach ($users as $user) {
            $usersNode->appendChild(
                $this->serializeUser($user)
            );
        }

        $this->appendAttributeNodes(
            $usersNode,
            self::CLASS_USERS,
            self::$DOM_USERS_ATTRIBUTES,
            $users);

        return $usersNode;
    }

    /**
     * @param Group $group
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeGroup(Group $group): DOMElement
    {

        $groupNode = $this->doc->createElement('group');

        $newNode = $this->doc->appendChild($groupNode);

        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_GROUP,
            self::$DOM_GROUP_ATTRIBUTES,
            $group);

        return $newNode;
    }

    /**
     * @param Groups $groups
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeGroups(Groups $groups): DOMElement
    {
        $groupsNode = $this->doc->createElement('groups');

        foreach ($groups as $group) {
            $groupsNode->appendChild(
                $this->serializeGroup($group)
            );
        }

        $this->appendAttributeNodes(
            $groupsNode,
            self::CLASS_GROUPS,
            self::$DOM_GROUPS_ATTRIBUTES,
            $groups);

        return $groupsNode;
    }

    /**
     * @param Document $document
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeDocument(Document $document): DOMElement
    {
        $docNode = $this->doc->createElement('document');
        $this->appendAttributeNodes($docNode, self::CLASS_DOCUMENT, self::$DOM_DOCUMENT_ATTRIBUTES, $document);

        return $docNode;
    }

    /**
     * @param Documents $documents
     *
     * @return DOMElement
     * @throws DOMException
     */
    function serializeDocuments(Documents $documents): DOMElement
    {
        $documentsNode = $this->doc->createElement('documents');

        foreach ($documents as $document) {
            $documentsNode->appendChild(
                $this->serializeDocument($document)
            );
        }

        $this->appendAttributeNodes(
            $documentsNode,
            self::CLASS_DOCUMENTS,
            self::$DOM_DOCUMENTS_ATTRIBUTES,
            $documents);

        return $documentsNode;
    }

    /**
     * @param Person $person
     * @return DOMElement
     * @throws DOMException
     */
    function serializePerson(Person $person): DOMElement
    {
        $personNode = $this->doc->createElement('person');

        $newNode = $this->doc->appendChild($personNode);

        if (!empty($person->getMainName())) {
            $mainNameNode = $this->doc->createElement('mainName');
            $nameArr = explode(' ', $person->getMainName());
            $mainNameNode->setAttribute('firstName', $nameArr[0]);
            $mainNameNode->setAttribute('lastName', $nameArr[1]);
            $newNode->appendChild($mainNameNode);
        }

        if (!empty($person->getUser())) {
            $userNode = $this->doc->createElement('user');
            $userNode->setAttribute('name', $person->getUser());
            $newNode->appendChild($userNode);
        }

        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_PERSON,
            self::$DOM_PERSON_ATTRIBUTES,
            $person);

        return $newNode;
    }

    /**
     * @param Persons $persons
     * @return DOMElement
     * @throws DOMException
     */
    function serializePersons(Persons $persons): DOMElement
    {
        $personsNode = $this->doc->createElement('persons');

        foreach ($persons as $person) {
            $personsNode->appendChild(
                $this->serializePerson($person)
            );
        }

        $this->appendAttributeNodes(
            $personsNode,
            self::CLASS_PERSONS,
            self::$DOM_PERSONS_ATTRIBUTES,
            $persons);

        return $personsNode;
    }

    /**
     * @param ResourcePersonRelation $relation
     * @return DOMElement
     * @throws DOMException
     */
    function serializeResourcePersonRelation(ResourcePersonRelation $relation): DOMElement
    {
        $relationNode = $this->doc->createElement('resourcePersonRelation');

        $newNode = $this->doc->appendChild($relationNode);

        if (!empty($relation->getPerson())) {
            $newNode->appendChild(
                $this->serializePerson($relation->getPerson())
            );
        }

        if (!empty($relation->getResourceLink())) {
            $newNode->appendChild(
                $this->serializeResourceLink($relation->getResourceLink())
            );
        }

        if (!empty($relation->getRelationType())) {
            $relationTypeNode = $this->doc->createElement('relationType', $relation->getRelationType());
            $newNode->appendChild($relationTypeNode);
        }

        if (!empty($relation->getPersonIndex())) {
            $personIndexNode = $this->doc->createElement('personIndex', $relation->getPersonIndex());
            $newNode->appendChild($personIndexNode);
        }

        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_RESOURCE_PERSON_RELATION,
            self::$DOM_RESOURCE_PERSON_RELATION_ATTRIBUTES,
            $relation);

        return $newNode;
    }

    /**
     * @param ResourcePersonRelations $relations
     * @return DOMElement
     * @throws DOMException
     */
    function serializeResourcePersonRelations(ResourcePersonRelations $relations): DOMElement
    {
        $relationsNode = $this->doc->createElement('resourcePersonRelations');

        foreach ($relations as $relation) {
            $relationsNode->appendChild(
                $this->serializeResourcePersonRelation($relation)
            );
        }

        $this->appendAttributeNodes(
            $relationsNode,
            self::CLASS_RESOURCE_PERSON_RELATIONS,
            self::$DOM_RESOURCE_PERSON_RELATIONS_ATTRIBUTES,
            $relations);

        return $relationsNode;
    }

    /**
     * @param Model\Project $project
     * @return DOMElement
     * @throws DOMException
     */
    function serializeProject(Model\Project $project): DOMElement
    {
        $projectNode = $this->doc->createElement('project');

        $newNode = $this->doc->appendChild($projectNode);

        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_PROJECT,
            self::$DOM_PROJECT_ATTRIBUTES,
            $project);

        return $newNode;
    }

    /**
     * @param Projects $projects
     * @return DOMElement
     * @throws DOMException
     */
    function serializeProjects(Projects $projects): DOMElement
    {
        $projectsNode = $this->doc->createElement('projects');

        foreach ($projects as $project) {
            $projectsNode->appendChild(
                $this->serializeProject($project)
            );
        }

        $this->appendAttributeNodes(
            $projectsNode,
            self::CLASS_PROJECTS,
            self::$DOM_PROJECTS_ATTRIBUTES,
            $projects);

        return $projectsNode;
    }

    /**
     * @param ResourceLink $resourceLink
     * @return DOMElement
     * @throws DOMException
     */
    function serializeResourceLink(ResourceLink $resourceLink): DOMElement
    {
        $resourceLinkNode = $this->doc->createElement('resource');

        $newNode = $this->doc->appendChild($resourceLinkNode);

        $this->appendAttributeNodes(
            $newNode,
            self::CLASS_RESOURCE_LINK,
            self::$DOM_RESOURCE_ATTRIBUTES,
            $resourceLink);

        return $newNode;
    }

    /**
     * @param DOMElement $domNode
     * @param $modelClassName
     * @param array $attributeList
     * @param $modelObject
     * @return void
     */
    private function appendAttributeNodes(DOMElement &$domNode, $modelClassName, array $attributeList, $modelObject)
    {
        try {
            $reflClass = new ReflectionClass($modelClassName);
        } catch (ReflectionException $e) {
            return;
        }

        foreach ($attributeList as $attr) {

            try {
                $method = $reflClass->getMethod('get' . ucfirst($attr));
                $retVal = $method->invoke($modelObject);
            } catch (ReflectionException $e) {
                continue;
            }
            if ($retVal !== '' && $retVal !== null) {
                $domNode->setAttribute($attr, $retVal);
            }
        }
    }

    /**
     *
     * @param ModelObject $model
     * @return string XML formatted string
     * @throws DOMException
     */
    public function render(ModelObject $model): string
    {
        return $this->renderXML(parent::render($model));
    }

    /**
     * @param DOMNode|null $node
     *
     * @return string
     * @throws DOMException
     */
    private function renderXML(DOMNode $node = null): string
    {
        if (null !== $node) {
            $rootNode = $this->doc->createElement('bibsonomy');
            $rootNode->appendChild($node);
            return $this->doc->saveXML($rootNode, LIBXML_NOBLANKS);
        }
        return $this->doc->saveXML();
    }

}

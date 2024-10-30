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

namespace AcademicPuma\RestClient;

use AcademicPuma\RestClient\Authentication\Accessor;
use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Config\ModelUtils;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Logic\LogicInterface;
use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Model\Exceptions\InvalidModelObjectException;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\ModelObject;
use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Model\User;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Queries\Delete\DeleteConceptQuery;
use AcademicPuma\RestClient\Queries\Delete\DeletePersonRelationQuery;
use AcademicPuma\RestClient\Queries\Delete\DeletePostDocumentQuery;
use AcademicPuma\RestClient\Queries\Delete\DeletePostQuery;
use AcademicPuma\RestClient\Queries\Get\GetConceptDetailsQuery;
use AcademicPuma\RestClient\Queries\Get\GetConceptsQuery;
use AcademicPuma\RestClient\Queries\Get\GetDocumentQuery;
use AcademicPuma\RestClient\Queries\Get\GetGroupDetailsQuery;
use AcademicPuma\RestClient\Queries\Get\GetGroupListQuery;
use AcademicPuma\RestClient\Queries\Get\GetPersonDetailsQuery;
use AcademicPuma\RestClient\Queries\Get\GetPersonListOfRelationsQuery;
use AcademicPuma\RestClient\Queries\Get\GetPersonListQuery;
use AcademicPuma\RestClient\Queries\Get\GetPostDetailsQuery;
use AcademicPuma\RestClient\Queries\Get\GetPostsQuery;
use AcademicPuma\RestClient\Queries\Get\GetTagDetailsQuery;
use AcademicPuma\RestClient\Queries\Get\GetTagRelationQuery;
use AcademicPuma\RestClient\Queries\Get\GetTagsQuery;
use AcademicPuma\RestClient\Queries\Get\GetUserDetailsQuery;
use AcademicPuma\RestClient\Queries\Get\GetUserListOfGroupQuery;
use AcademicPuma\RestClient\Queries\Get\GetUserListQuery;
use AcademicPuma\RestClient\Queries\Post\CreateConceptQuery;
use AcademicPuma\RestClient\Queries\Post\CreatePersonQuery;
use AcademicPuma\RestClient\Queries\Post\CreatePersonRelationQuery;
use AcademicPuma\RestClient\Queries\Post\CreatePostDocumentQuery;
use AcademicPuma\RestClient\Queries\Post\CreatePostQuery;
use AcademicPuma\RestClient\Queries\Post\CreateUserRelationshipQuery;
use AcademicPuma\RestClient\Queries\Put\ChangeConceptQuery;
use AcademicPuma\RestClient\Queries\Put\ChangeDocumentNameQuery;
use AcademicPuma\RestClient\Queries\Put\ChangePersonQuery;
use AcademicPuma\RestClient\Queries\Put\ChangePostQuery;
use DOMException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;
use ReflectionException;

/**
 * PHP REST-client for BibSonomy API and PUMA API.
 *
 * @author Florian Fassing
 */
class RESTClient implements LogicInterface
{
    private $httpClient;
    private $reqOpts;
    private $query;

    /**
     *
     * @param Accessor $accessor Holds authentication data.
     * @param array $reqOpts curl request options
     */
    public function __construct(Accessor $accessor, array $reqOpts = [])
    {
        $this->httpClient = $accessor->getClient();
        $this->reqOpts = $reqOpts;
    }

    /**
     * Model Object representation of the requested API Result.
     *
     * @param int $treatCurlyBraces Determines how to treat curly braces in title, abstract and author field.
     * @param int $treatBackslashes Determines how to treat backslashes in title, abstract and author field.
     * @param bool $bibTexCleaning Determines whether BibTex cleaning will be executed.
     * @return ModelObject
     * @throws UnsupportedOperationException
     * @throws DOMException
     * @throws ReflectionException
     */
    public function model(int  $treatCurlyBraces = ModelUtils::CB_KEEP,
                          int  $treatBackslashes = ModelUtils::BS_KEEP,
                          bool $bibTexCleaning = true): ModelObject
    {
        return $this->query->model($treatCurlyBraces, $treatBackslashes, $bibTexCleaning);
    }


    /**
     * @return StreamInterface
     *
     */
    public function file(): StreamInterface
    {
        return $this->query->getStream();
    }

    /**
     *
     * @return string
     * @throws UnsupportedOperationException
     */
    public function getBody(): string
    {
        return $this->query->getBody();
    }

    /**
     * Returns the Query object of the last used request. Null, if no request has been called before.
     *
     * @return AbstractQuery
     */
    public function getQuery(): AbstractQuery
    {
        return $this->query;
    }

    /**
     *
     * @param string $userName
     * @param string $resourceHash
     * @param Document $document
     * @param string $filePath
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     */
    public function changeDocumentName(string $userName, string $resourceHash, Document $document, string $filePath): RESTClient
    {
        $this->query = new ChangeDocumentNameQuery($filePath, $resourceHash, $userName, $document);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * POST /users/[userName]/concepts/[conceptName]
     *
     * Create a new relation/concept
     * note: if a concept already exists with the given name
     * it will be replaced
     *
     * @param Tag $concept the tag containing subTags
     * @param string $conceptName the name of the super tag of the concept
     * @param string $userName the user name of the user the concept belongs to.
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     */
    public function createConcept(Tag $concept, string $conceptName, string $userName): RESTClient
    {
        $this->query = new CreateConceptQuery($concept, $userName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * Adds a document to a post.
     *
     * @param string $userName
     * @param string $resourceHash
     * @param string $fileName
     * @param string $filePath
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function createDocument(string $userName, string $resourceHash, string $fileName, string $filePath): RESTClient
    {
        $this->query = new CreatePostDocumentQuery($userName, $resourceHash, $fileName, $filePath);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * POST /api/users/[username]/posts
     *
     * Add post(s) to an user's collection.
     *
     * @param Post|Posts $posts the post(s) to add
     * @param string $userName The username under which the posts will be added.
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function createPosts($posts, string $userName): RESTClient
    {
        $this->query = new CreatePostQuery($posts, $userName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * We create a UserRelation of the form (sourceUser, targetUser)\in relation
     * sourceUser should be logged in for this
     *
     * @param User $sourceUser leftHandSide of the relation
     * @param User $targetUser rightHandSide of the relation
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     */
    public function createUserRelationship(User $sourceUser, User $targetUser): RESTClient
    {
        $this->query = new CreateUserRelationshipQuery($sourceUser, $targetUser);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: POST
     * URL: /api/persons/
     *
     * Request to add a new person and returns that person's generated personId in the system.
     *
     * @param Person $person
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function createPerson(Person $person): RESTClient
    {
        $this->query = new CreatePersonQuery($person);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: POST
     * URL: /api/persons/[personid]/relations
     *
     * Request to add a new resource-person-relation to that person's relation collection.
     *
     * @param string $personId
     * @param ResourcePersonRelation $relation
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function createPersonRelation(string $personId, ResourcePersonRelation $relation): RESTClient
    {
        $this->query = new CreatePersonRelationQuery($personId, $relation);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }


    /**
     *
     * DELETE /users/[username]/concepts/[conceptname]
     *
     * Delete an existing concept
     *
     * @param string $conceptName - name of the concept to delete
     * @param string $userName
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function deleteConcept(string $conceptName, string $userName): RESTClient
    {
        $this->query = new DeleteConceptQuery($conceptName, $userName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * Deletes an existing document. If the resourceHash is given, the document
     * is assumed to be connected to the corresponding resource (identified by
     * the user name in the document). Otherwise the document is independent of
     * any post.
     *
     * @param string $userName user name of the post/document owner
     * @param string $resourceHash the intraHash of the post the document belongs to.
     * @param string $fileName fileName of the document which should be deleted.
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function deleteDocument(string $userName, string $resourceHash, string $fileName): RESTClient
    {
        $this->query = new DeletePostDocumentQuery($userName, $resourceHash, $fileName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * Removes the given post - identified by the connected resource's hash -
     * from the user.
     *
     * @param string $userName user who's posts are to be removed
     * @param string $resourceHash hash of the resource, which is connected to the post to delete
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function deletePosts(string $userName, string $resourceHash): RESTClient
    {
        $this->query = new DeletePostQuery($userName, $resourceHash);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: DELETE
     * URL: /api/persons/[personid]/relations/[interhash]/[type]/[index]
     *
     * A request to delete a specific resource-person relation.
     * The interhash of the resource, the relationtype and the personindex are required for this request.
     *
     * @param string $personId
     * @param string $resourceHash
     * @param string $type
     * @param string $index
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function deletePersonRelation(string $personId, string $resourceHash, string $type, string $index): RESTClient
    {
        $this->query = new DeletePersonRelationQuery($personId, $resourceHash, $type, $index);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * GET /users/[userName]/concepts/[conceptName]
     * GET /concepts/[conceptName]
     *
     * Retrieve Details for a concept, containing the belonging subTags.
     *
     * @param string $conceptName the supertag of the concept
     * @param string $userName the user name of the user the concept belongs to. If null, system-wide relations will
     *                            be returned.
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getConceptDetails(string $conceptName, string $userName): RESTClient
    {
        $this->query = new GetConceptDetailsQuery($conceptName, $userName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * /concepts
     *
     * Retrieve relations
     *
     * @param string $resourceType the requested resourcetype
     * @param string $grouping grouping entity
     * @param string $groupingName the grouping name
     * @param array $tags a list of tags which shall be part of the relations
     * @param string|null $regex a regex to possibly filter the relatons retrieved
     * @param string|null $status the conceptstatus, i.e. all, picked or unpicked
     * @param int $start start index
     * @param int $end end index
     *
     * @return RESTClient
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function getConcepts(string $resourceType, string $grouping, string $groupingName, array $tags, ?string $regex, ?string $status, int $start = 0, int $end = 20): RESTClient
    {
        $this->query = new GetConceptsQuery($resourceType, $grouping, $groupingName, $tags, $regex, $status, $start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * Get a document from a publication resource.
     *
     * @param string $userName name of the publication owner
     * @param string $resourceHash intraHash of the post resource
     * @param string $fileName file name of the document
     * @param string $type determines whether a picture or the actual file will be received
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getDocumentFile(string $userName, string $resourceHash, string $fileName, string $type = Config\DocumentType::FILE): RESTClient
    {
        $this->query = new GetDocumentQuery($userName, $resourceHash, $fileName, $type);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * /groups/[groupName]
     *
     * Returns details of one group.
     *
     * @param string $groupName
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getGroupDetails(string $groupName): RESTClient
    {
        $this->query = new GetGroupDetailsQuery($groupName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * /groups
     *
     * Returns all groups of the system.
     *
     * @param integer $start
     *
     * @param integer $end
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getGroups(int $start, int $end): RESTClient
    {
        $this->query = new GetGroupListQuery($start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * Returns details to a post. A post is uniquely identified by a hash of the
     * corresponding resource and a username.
     *
     * @param string $userName name of the post-owner
     *
     * @param string $resourceHash hash value of the corresponding resource
     * @param string $format Default value is 'xml'. If you want to use the model or any ModelRenderer, please
     *                             keep it empty or use 'xml'.
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getPostDetails(string $userName, string $resourceHash, string $format = 'xml'): RESTClient
    {
        $this->query = new GetPostDetailsQuery($userName, $resourceHash, $format);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * @param string $resourceType resource type (bookmark|bibtex)
     * @param string $grouping grouping tells whom posts are to be shown: the posts of a
     *                                  user, of a group or of the viewables.
     * @param string $groupingName name of the grouping. if grouping is user, then its the
     *                                  username. if grouping is set to {@link GroupingEntity#ALL},
     *                                  then its an empty string!
     * @param array $tags a set of tags
     * @param ?string $hash intraHash value of a resource, if one would like to get a list of
     *                                  all posts belonging to a given resource.
     * @param ?string $search free text search
     * @param array $sortKeys a list of keys to sort the posts by
     * @param array $sortOrders a list of sort orders to set the order for the keys
     * @param int $start inclusive start index of the view window
     * @param int $end exclusive end index of the view window
     * @param string $format Format of received post (xml|json|csl|bibtex|endnote).
     *                                  Default value is 'xml'. If you want to use the model or any ModelRenderer,
     *                                  please keep it empty or use 'xml'.
     * @param string $searchType Default value is 'searchindex'.
     *                              'searchindex' request will search against the searchindex and return fully sorted list of posts.
     *                              'local' requests search against the database are more accurate to recent changes.
     *
     * @return RESTClient
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function getPosts(string  $resourceType,
                             string  $grouping,
                             string  $groupingName,
                             array   $tags = [],
                             ?string $hash = null,
                             ?string $search = null,
                             array   $sortKeys = [],
                             array   $sortOrders = [],
                             string  $searchType = 'searchindex',
                             int     $start = 0,
                             int     $end = 20,
                             string  $format = 'xml'): RESTClient
    {
        $this->query = new GetPostsQuery($resourceType, $grouping, $groupingName, $tags, $hash, $search,
            $sortKeys, $sortOrders, $searchType, $start, $end, $format);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * /tags/[tag]
     *
     * Returns details about a tag. Those details are:
     * <ul>
     * <li>details about the tag itself, like number of occurrences etc</li>
     * <li>list of subtags</li>
     * <li>list of supertags</li>
     * <li>list of correlated tags</li>
     * </ul>
     *
     * @param string $tagName name of the tag
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getTagDetails(string $tagName): RESTClient
    {
        $this->query = new GetTagDetailsQuery($tagName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * @param string $grouping
     * @param string $groupingName
     * @param string $relation
     * @param array $tags
     * @param string|null $order
     * @param integer $start start position
     * @param int $end end position
     *
     * @return RESTClient
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function getTagRelation(string $grouping, string $groupingName, string $relation, array $tags, ?string $order = null, int $start = 0, int $end = 20): RESTClient
    {
        $this->query = new GetTagRelationQuery($grouping, $groupingName, $relation, $tags, $order, $start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * /tags ?filter=[regex] ?(user|group|viewable)=[username/groupname] ?order=(frequency|alph)
     *
     * Returns a list of tags which can be filtered.
     *
     * @param string $grouping
     *                       grouping tells whom tags are to be shown: the tags of a user,
     *                       of a group or of the viewables.
     * @param string $groupingName
     *                       name of the grouping. if grouping is user, then its the
     *                       username. if grouping is set to {@link GroupingEntity#ALL},
     *                       then its an empty string!
     * @param string|null $regex a regular expression used to filter the tagnames
     * @param string|null $order
     * @param integer $start
     * @param integer $end
     *
     * @return RESTClient
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function getTags(string $grouping, string $groupingName, ?string $regex = null, ?string $order = null, int $start = 0, int $end = 20): RESTClient
    {
        $this->query = new GetTagsQuery($grouping, $groupingName, $regex, $order, $start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * /users/[username]
     *
     * Returns details about a specified user
     *
     * @param string $userName name of the user we want to get details from
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getUserDetails(string $userName): RESTClient
    {
        $this->query = new GetUserDetailsQuery($userName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     *
     * GET    /groups/[groupName]/users
     *
     * @param string $groupName
     * @param integer $start
     * @param integer $end
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getUserListOfGroup(string $groupName, int $start, int $end): RESTClient
    {
        $this->query = new GetUserListOfGroupQuery($groupName, $start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * URL: /users
     *
     *
     * Generic method to retrieve lists of users
     *
     * @param integer $start start position
     * @param integer $end end position
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getUsers(int $start = 0, int $end = 20): RESTClient
    {
        $this->query = new GetUserListQuery($start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: GET
     * URL: /api/persons
     *
     * A request to get a list of persons.
     *
     * @param int $start
     * @param int $end
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getPersons(int $start, int $end): RESTClient
    {
        $this->query = new GetPersonListQuery($start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: GET
     * URL: /api/persons/[personid]
     *
     * A request to get details of a person specified by their personId.
     *
     * @param string $personId
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getPersonDetails(string $personId): RESTClient
    {
        $this->query = new GetPersonDetailsQuery($personId);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: GET
     * URL: /api/persons/[personid]/relations
     *
     * A request to get a list of relations to posts of the person specified by their personId.
     * The relations only contain the interhash and intrahash of a post.
     *
     * @param string $personId
     * @param int $start
     * @param int $end
     *
     * @return RESTClient
     * @throws GuzzleException
     */
    public function getPersonListOfRelations(string $personId, int $start = 0, int $end = 20): RESTClient
    {
        $this->query = new GetPersonListOfRelationsQuery($personId, $start, $end);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * PUT /users/[username]/concepts/[conceptname]
     *
     * Update an existing relation/concept
     *
     * @param Tag $concept the concept to update
     * @param string $userName name of the user who created the concept
     * @param string $operation
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     */
    public function updateConcept(Tag $concept, string $userName, string $operation): RESTClient
    {
        $this->query = new ChangeConceptQuery($concept, $userName, $operation);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * Updates the post in the database.
     *
     * @param Post $post post to update
     * @param string $userName user name of the post owner
     * @param string $resourceHash of the updated post
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     * @throws InvalidModelObjectException
     * @throws UnsupportedOperationException
     */
    public function updatePost(Post $post, string $userName, string $resourceHash): RESTClient
    {
        $this->query = new ChangePostQuery($post, $userName);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }

    /**
     * HTTP Method: PUT
     * URL: /api/persons/[personid]
     *
     * A request to update details of a person specified by their personId.
     *
     * @param string $personId
     * @param Person $person
     *
     * @return RESTClient
     * @throws DOMException
     * @throws GuzzleException
     * @throws UnsupportedOperationException
     */
    public function updatePerson(string $personId, Person $person): RESTClient
    {
        $this->query = new ChangePersonQuery($personId, $person);
        $this->query->execute($this->httpClient, $this->reqOpts);

        return $this;
    }
}

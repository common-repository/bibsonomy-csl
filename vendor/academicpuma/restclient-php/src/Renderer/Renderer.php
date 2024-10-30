<?php

/*
 *  restclient-php is a full-featured REST client for PUMA and/or
 *  BibSonomy.
 *
 *  Copyright (C) 2022
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

use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Model\Documents;
use AcademicPuma\RestClient\Model\Group;
use AcademicPuma\RestClient\Model\Groups;
use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Model\Persons;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Model\Project;
use AcademicPuma\RestClient\Model\Projects;
use AcademicPuma\RestClient\Model\Resource;
use AcademicPuma\RestClient\Model\ResourceLink;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Model\ResourcePersonRelations;
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Model\Tags;
use AcademicPuma\RestClient\Model\User;
use AcademicPuma\RestClient\Model\Users;

/**
 * Renderer interfaces for all the supported model classes.
 *
 * @author kchoong
 */
interface Renderer
{
    /**
     * Serializes a <pre>$resource</pre> into its XML representation
     *
     * @param Resource $resource
     */
    function serializeResource(Resource $resource);

    /**
     * Serializes a <pre>$post</pre> recursively into its XML representation
     *
     * @param Post $post
     */
    function serializePost(Post $post);

    /**
     * Serializes <pre>$posts </pre> recursively into its XML representation
     *
     * @param Posts $posts
     */
    function serializePosts(Posts $posts);

    /**
     * Serializes a <pre>$tag</pre> into its XML representation
     *
     * @param Tag $tag
     */
    function serializeTag(Tag $tag);


    /**
     * Serializes a set of <pre>$tags</pre> into its XML representation
     *
     * @param Tags $tags
     */
    function serializeTags(Tags $tags);

    /**
     * Serializes a <pre>$user</pre> into its XML representation
     *
     * @param User $user
     */
    function serializeUser(User $user);

    /**
     * Serializes a set of <pre>$users</pre> into its XML representation
     *
     * @param Users $users
     */
    function serializeUsers(Users $users);

    /**
     * Serializes a <pre>$group</pre> into its XML representation
     *
     * @param Group $group
     */
    function serializeGroup(Group $group);

    /**
     * Serializes a set of <pre>$groups</pre> into its XML representation
     *
     * @param Groups $groups
     */
    function serializeGroups(Groups $groups);

    /**
     * Serializes a <pre>$document</pre> into its XML representation
     *
     * @param Document $document
     */
    function serializeDocument(Document $document);

    /**
     * Serializes a set of <pre>$documents</pre> into its XML representation
     *
     * @param Documents $documents
     */
    function serializeDocuments(Documents $documents);

    /**
     * Serializes a <pre>$person</pre> into its XML representation
     *
     * @param Person $person
     */
    function serializePerson(Person $person);

    /**
     * Serializes a set of <pre>$persons</pre> into its XML representation
     *
     * @param Persons $persons
     */
    function serializePersons(Persons $persons);

    /**
     * Serializes a <pre>$relation</pre> into its XML representation
     * @param ResourcePersonRelation $relation
     */
    function serializeResourcePersonRelation(ResourcePersonRelation $relation);

    /**
     * Serializes a set of <pre>$relations</pre> into its XML representation
     *
     * @param ResourcePersonRelations $relations
     */
    function serializeResourcePersonRelations(ResourcePersonRelations $relations);

    /**
     * Serializes a <pre>$project</pre> into its XML representation
     * @param Project $project
     */
    function serializeProject(Project $project);

    /**
     * Serializes a set of <pre>$project</pre> into its XML representation
     * @param Projects $projects
     */
    function serializeProjects(Projects $projects);

    /**
     * Serializes a <pre>$resourceLink</pre> into its XML representation
     * @param ResourceLink $resourceLink
     */
    function serializeResourceLink(ResourceLink $resourceLink);
}
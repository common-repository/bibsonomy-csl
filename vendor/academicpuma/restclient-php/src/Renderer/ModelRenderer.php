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
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\Group;
use AcademicPuma\RestClient\Model\Groups;
use AcademicPuma\RestClient\Model\ModelObject;
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
 * Description of Renderer
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
abstract class ModelRenderer implements Renderer
{
    /**
     * Serializes a <pre>$resource</pre> into its XML representation
     *
     * @param Resource $resource
     * @throws UnsupportedOperationException
     */
    function serializeResource(Resource $resource)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$post</pre> recursively into its XML representation
     *
     * @param Post $post
     * @throws UnsupportedOperationException
     */
    function serializePost(Post $post)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes <pre>$posts </pre> recursively into its XML representation
     *
     * @param Posts $posts
     * @throws UnsupportedOperationException
     */
    function serializePosts(Posts $posts)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$tag</pre> into its XML representation
     *
     * @param Tag $tag
     * @throws UnsupportedOperationException
     */
    function serializeTag(Tag $tag)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$tags</pre> into its XML representation
     *
     * @param Tags $tags
     * @throws UnsupportedOperationException
     */
    function serializeTags(Tags $tags)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$user</pre> into its XML representation
     *
     * @param User $user
     * @throws UnsupportedOperationException
     */
    function serializeUser(User $user)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$users</pre> into its XML representation
     *
     * @param Users $users
     * @throws UnsupportedOperationException
     */
    function serializeUsers(Users $users)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$group</pre> into its XML representation
     *
     * @param Group $group
     * @throws UnsupportedOperationException
     */
    function serializeGroup(Group $group)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$groups</pre> into its XML representation
     *
     * @param Groups $groups
     * @throws UnsupportedOperationException
     */
    function serializeGroups(Groups $groups)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$document</pre> into its XML representation
     *
     * @param Document $document
     * @throws UnsupportedOperationException
     */
    function serializeDocument(Document $document)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$documents</pre> into its XML representation
     *
     * @param Documents $documents
     * @throws UnsupportedOperationException
     */
    function serializeDocuments(Documents $documents)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$person</pre> into its XML representation
     *
     * @param Person $person
     * @throws UnsupportedOperationException
     */
    function serializePerson(Person $person)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$persons</pre> into its XML representation
     *
     * @param Persons $persons
     * @throws UnsupportedOperationException
     */
    function serializePersons(Persons $persons)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$relation</pre> into its XML representation
     *
     * @param ResourcePersonRelation $relation
     * @throws UnsupportedOperationException
     */
    function serializeResourcePersonRelation(ResourcePersonRelation $relation)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$relations</pre> into its XML representation
     *
     * @param ResourcePersonRelations $relations
     * @throws UnsupportedOperationException
     */
    function serializeResourcePersonRelations(ResourcePersonRelations $relations)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$project</pre> into its XML representation
     *
     * @param Project $project
     * @throws UnsupportedOperationException
     */
    function serializeProject(Project $project)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a set of <pre>$project</pre> into its XML representation
     *
     * @param Projects $projects
     * @throws UnsupportedOperationException
     */
    function serializeProjects(Projects $projects)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Serializes a <pre>$resourceLink</pre> into its XML representation
     *
     * @param ResourceLink $resourceLink
     * @throws UnsupportedOperationException
     */
    function serializeResourceLink(ResourceLink $resourceLink)
    {
        throw new UnsupportedOperationException(__METHOD__ . " not supported in this Model Renderer.");
    }

    /**
     * Renders a <pre>ModelObject $object</pre> recursively into its representation
     *
     * @param ModelObject $model Object of type <pre>ModelObject</pre>
     *
     * @return null
     */
    public function render(ModelObject $model)
    {
        try {
            if ($model instanceof Posts) {
                return $this->serializePosts($model);
            }

            if ($model instanceof Tags) {
                return $this->serializeTags($model);
            }

            if ($model instanceof Users) {
                return $this->serializeUsers($model);
            }

            if ($model instanceof Groups) {
                return $this->serializeGroups($model);
            }

            if ($model instanceof Documents) {
                return $this->serializeDocuments($model);
            }

            if ($model instanceof Persons) {
                return $this->serializePersons($model);
            }

            if ($model instanceof ResourcePersonRelations) {
                return $this->serializeResourcePersonRelations($model);
            }

            if ($model instanceof Projects) {
                return $this->serializeProjects($model);
            }

            if ($model instanceof Post) {
                return $this->serializePost($model);
            }

            if ($model instanceof Resource) {
                return $this->serializeResource($model);
            }

            if ($model instanceof User) {
                return $this->serializeUser($model);
            }

            if ($model instanceof Tag) {
                return $this->serializeTag($model);
            }

            if ($model instanceof Group) {
                return $this->serializeGroup($model);
            }

            if ($model instanceof Document) {
                return $this->serializeDocument($model);
            }

            if ($model instanceof Person) {
                return $this->serializePerson($model);
            }

            if ($model instanceof ResourcePersonRelation) {
                return $this->serializeResourcePersonRelation($model);
            }

            if ($model instanceof ResourceLink) {
                return $this->serializeResourceLink($model);
            }

            if ($model instanceof Project) {
                return $this->serializeProject($model);
            }
        } catch (UnsupportedOperationException $e) {

        }

        return null;
    }
}

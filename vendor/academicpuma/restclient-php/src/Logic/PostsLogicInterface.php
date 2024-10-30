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

namespace AcademicPuma\RestClient\Logic;

use AcademicPuma\RestClient\Config\DocumentType;
use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Model\Exceptions\ResourceNotFoundException;
use AcademicPuma\RestClient\Model\ModelObject;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\RESTClient;

/**
 * Interface PostLogicInterface
 *
 * @package AcademicPuma\RestClient\Logic
 *
 * @author  Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
interface PostsLogicInterface
{

    /**
     * @param string $resourceType resource type (bookmark|bibtex)
     * @param string $grouping grouping tells whom posts are to be shown: the posts of a
     *                              user, of a group or of the viewables.
     * @param string $groupingName name of the grouping. if grouping is user, then its the
     *                              username. if grouping is set to {@link GroupingEntity#ALL},
     *                              then its an empty string!
     * @param array $tags a set of tags
     * @param string|null $hash intraHash value of a resource, if one would like to get a list of
     *                              all posts belonging to a given resource.
     * @param string|null $search free text search
     * @param array $sortKeys a list of keys to sort the posts by
     * @param array $sortOrders a list of sort orders to set the order for the keys
     * @param string $searchType set the searchtype (local|searchindex)
     *                              Default value is 'searchindex'.
     *                              'searchindex' request will search against the searchindex and return fully sorted list of posts.
     *                              'local' requests search against the database are more accurate to recent changes.
     *
     * @param int $start inclusive start index of the view window
     * @param int $end exclusive end index of the view window
     * @param string $format Format of received post (xml|json|csl|bibtex|endnote).
     *                              Default value is 'xml'. If you want to use the model or any ModelRenderer, please
     *                              keep it empty or use 'xml'.
     * @return RESTClient
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
                             string  $format = 'xml'): RESTClient;

    /**
     * Returns details to a post. A post is uniquely identified by a hash of the
     * corresponding resource and a username.
     *
     * @param string $resourceHash hash value of the corresponding resource
     * @param string $userName name of the post-owner
     *
     * @return RESTClient
     * @throws ResourceNotFoundException if the resource of a given resourceHash could not be found
     */
    public function getPostDetails(string $userName, string $resourceHash): RESTClient;

    /**
     * Removes the given post - identified by the connected resource's hash -
     * from the user.
     *
     * @param string $userName user who's posts are to be removed
     * @param string $resourceHash hash of the resource, which is connected to the post to delete
     *
     * @return RESTClient
     */
    public function deletePosts(string $userName, string $resourceHash): RESTClient;

    /**
     * POST /api/users/[username]/posts
     *
     * Add post(s) to an user's collection.
     *
     * @param Post|Posts $posts the post(s) to add
     * @param string $userName The username under which the posts will be added
     *
     * @return RESTClient
     */
    public function createPosts($posts, string $userName): RESTClient;

    /**
     * Updates the post in the database.
     *
     * @param Post $post
     * @param string $userName user name of the post owner
     * @param string $resourceHash of the post
     *
     * @return RESTClient
     * @internal param Model\Post $post the post to update
     */
    public function updatePost(Post $post, string $userName, string $resourceHash): RESTClient;

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
     */
    public function createDocument(string $userName, string $resourceHash, string $fileName, string $filePath): RESTClient;

    /**
     *
     * Adds a document to a post.
     *
     * @param string $userName
     * @param string $resourceHash
     * @param Document $document
     * @param string $filePath
     *
     * @return RESTClient
     */
    public function changeDocumentName(string $userName, string $resourceHash, Document $document, string $filePath): RESTClient;

    /**
     * @param string $userName
     * @param string $resourceHash
     * @param string $fileName
     * @param string $type (file|SMALL|MIDDLE|LARGE). 'file' returns the considered file (e.g. pdf), SMALL, MEDIUM,
     *                      LARGE return a png preview image. All options in
     *                      <code>\AcademicPuma\RestClient\Config\DocumentType</code>
     *
     * @return RESTClient
     */
    public function getDocumentFile(string $userName, string $resourceHash, string $fileName, string $type = DocumentType::FILE): RESTClient;

    /**
     *
     * Deletes an existing document. If the resourceHash is given, the document
     * is assumed to be connected to the corresponding resource (identified by
     * the user name in the document). Otherwise the document is independent of
     * any post.
     *
     * @param string $userName user name of the post/document owner
     * @param string $resourceHash the intraHash of the post the document belongs to
     * @param string $fileName fileName of the document which should be deleted
     *
     * @return RESTClient
     */
    public function deleteDocument(string $userName, string $resourceHash, string $fileName): RESTClient;
}

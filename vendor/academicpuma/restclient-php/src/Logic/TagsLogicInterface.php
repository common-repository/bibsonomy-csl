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

use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\RESTClient;

/**
 * Interface TagsLogicInterface
 *
 * @package AcademicPuma\RestClient\Logic
 *
 * @author  Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
interface TagsLogicInterface
{

    /**
     *
     * /tags ?filter=[regex] ?(user|group|viewable)=[username/groupname] ?order=(frequency|alph)
     *
     * Returns a list of tags which can be filtered.
     *
     * @param string $grouping
     *                      grouping tells whom tags are to be shown: the tags of a user,
     *                      of a group or of the viewables.
     * @param string $groupingName
     *                      name of the grouping. if grouping is user, then its the
     *                      username. if grouping is set to {@link GroupingEntity#ALL},
     *                      then its an empty string!
     * @param string|null $regex a regular expression used to filter the tagnames
     * @param string|null $order (frequency|alph)
     * @param int $start
     * @param int $end
     *
     * @return RESTClient
     */
    public function getTags(string $grouping, string $groupingName, ?string $regex = null, ?string $order = null, int $start = 0, int $end = 20): RESTClient;

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
     */
    public function getTagDetails(string $tagName): RESTClient;

    /**
     *
     * @param string $grouping
     * @param string $groupingName
     * @param string $relation
     * @param array $tags
     * @param string|null $order
     * @param int $start
     * @param int $end
     *
     * @return RESTClient
     *
     */
    public function getTagRelation(string $grouping, string $groupingName, string $relation, array $tags, ?string $order = null, int $start = 0, int $end = 20): RESTClient;

    /**
     * /concepts
     *
     * Retrieve relations
     *
     * @param string $resourceType the reqtested resourcetype
     * @param string $grouping grouping entity
     * @param string $groupingName the grouping name
     * @param array $tags a list of tags which shall be part of the relations
     * @param string|null $regex a regex to possibly filter the relatons retrieved
     * @param string|null $status the conceptstatus, i.e. all, picked or unpicked
     * @param int $start start index
     * @param int $end end index
     *
     * @return RESTClient
     */
    public function getConcepts(string $resourceType, string $grouping, string $groupingName, array $tags, ?string $regex, ?string $status, int $start = 0, int $end = 20): RESTClient;

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
     */
    public function getConceptDetails(string $conceptName, string $userName): RESTClient;

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
     */
    public function createConcept(Tag $concept, string $conceptName, string $userName): RESTClient;

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
     */
    public function updateConcept(Tag $concept, string $userName, string $operation): RESTClient;

    /**
     *
     * DELETE /users/[username]/concepts/[conceptName]
     *
     * Delete an existing concept
     *
     * @param string $conceptName name of the concept to delete
     * @param string $userName name of user holding the concept
     *
     * @return RESTClient
     */
    public function deleteConcept(string $conceptName, string $userName): RESTClient;

}
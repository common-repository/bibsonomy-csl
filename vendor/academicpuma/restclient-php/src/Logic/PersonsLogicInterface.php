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

use AcademicPuma\RestClient\Model\Person;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\RESTClient;

/**
 * Interface PersonsLogicInterface
 *
 * @package AcademicPuma\RestClient\Logic
 *
 * @author  kchoong
 */
interface PersonsLogicInterface
{

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
     */
    public function getPersons(int $start, int $end): RESTClient;

    /**
     * HTTP Method: GET
     * URL: /api/persons/[personid]
     *
     * A request to get details of a person specified by their personId.
     *
     * @param string $personId
     *
     * @return RESTClient
     */
    public function getPersonDetails(string $personId): RESTClient;

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
     */
    public function getPersonListOfRelations(string $personId, int $start = 0, int $end = 20): RESTClient;

    /**
     * HTTP Method: POST
     * URL: /api/persons/
     *
     * Request to add a new person and returns that person's generated personId in the system.
     *
     * @param Person $person
     *
     * @return RESTClient
     */
    public function createPerson(Person $person): RESTClient;

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
     */
    public function createPersonRelation(string $personId, ResourcePersonRelation $relation): RESTClient;

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
     */
    public function updatePerson(string $personId, Person $person): RESTClient;

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
     */
    public function deletePersonRelation(string $personId, string $resourceHash, string $type, string $index): RESTClient;

}
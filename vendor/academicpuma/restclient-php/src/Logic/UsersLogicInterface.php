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

use AcademicPuma\RestClient\Model\User;
use AcademicPuma\RestClient\RESTClient;

/**
 * Interface UserLogicInterface
 *
 * @package AcademicPuma\RestClient\Logic
 *
 * @author  Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
interface UsersLogicInterface
{

    /**
     * URL: /users
     *
     *
     * Generic method to retrieve lists of users
     *
     * @param int $start start position
     * @param int $end end position
     *
     * @return RESTClient
     */
    public function getUsers(int $start = 0, int $end = 20): RESTClient;

    /**
     *
     * /users/[username]
     *
     * Returns details about a specified user
     *
     * @param string $userName name of the user we want to get details from
     *
     * @return RESTClient
     */
    public function getUserDetails(string $userName): RESTClient;

    /**
     *
     * We create a UserRelation of the form (sourceUser, targetUser)\in relation
     * sourceUser should be logged in for this
     *
     * @param User $sourceUser leftHandSide of the relation
     * @param User $targetUser rightHandSie of the relation
     *
     * @return RESTClient
     */
    public function createUserRelationship(User $sourceUser, User $targetUser): RESTClient;

}
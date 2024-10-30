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

use AcademicPuma\RestClient\RESTClient;

/**
 * Interface GroupLogicInterface
 *
 * @package AcademicPuma\RestClient\Logic
 *
 * @author  Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
interface GroupsLogicInterface
{

    /**
     *
     * /groups
     *
     * Returns all groups of the system.
     *
     * @param int $start start index
     * @param int $end end index
     *
     * @return RESTClient
     */
    public function getGroups(int $start, int $end): RESTClient;

    /**
     *
     * /groups/[groupName]
     *
     * Returns details of one group.
     *
     * @param string $groupName name of the group
     *
     * @return RESTClient
     */
    public function getGroupDetails(string $groupName): RESTClient;

    /**
     *
     * GET    /groups/[groupName]/users
     *
     * @param string $groupName name of the group
     * @param int $start start index
     * @param int $end end index
     *
     * @return RESTClient
     */
    public function getUserListOfGroup(string $groupName, int $start, int $end): RESTClient;

}
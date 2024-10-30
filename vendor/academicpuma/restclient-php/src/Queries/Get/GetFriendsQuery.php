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

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 * Builds URL for GetFriends REST-API calls.
 * Also holds the response after being executed.
 *
 * Returns a list of users which either the requested user has in his friend list,
 * or all users, which have the requested user in his friend list.
 *
 * TODO: No method in RESTClient.php is using this query.
 *
 * @author Florian Fassing
 */
class GetFriendsQuery extends AbstractQuery
{
    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /users/[username]/friends ?relation=(incomingǀoutgoing)
     *
     * @param string $userName
     * @param string|null $relation
     * @param int $start start index
     * @param int $end end index
     */
    public function __construct(string $userName, int $start, int $end, string $relation = null)
    {
        parent::__construct();

        // Check whether start and end params make sense.
        if ($start < 0) {
            $start = 0;
        }
        if ($end < $start) {
            $end = $start;
        }

        // Default relation is incoming.
        if ($relation !== RESTConfig::INCOMING_ATTRIBUTE_VALUE_RELATION
            && $relation !== RESTConfig::OUTGOING_ATTRIBUTE_VALUE_RELATION) {
            $relation = RESTConfig::INCOMING_ATTRIBUTE_VALUE_RELATION;
        }

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $userName,
                RESTConfig::FRIENDS_SUB_PATH
            ],
            [// params
                RESTConfig::ATTRIBUTE_KEY_RELATION => $relation,
                RESTConfig::START_PARAM => $start,
                RESTConfig::END_PARAM => $end
            ]
        );
    }

    /**
     * Execute a prepared query.
     *
     * @param Client $client
     * @param array $reqOpts request options
     *
     * @return $this
     * @throws GuzzleException
     */
    public function execute(Client $client, array $reqOpts = []): AbstractQuery
    {
        $this->response = $client->get($this->url, $reqOpts);
        $this->executed = true;

        return $this;
    }
}

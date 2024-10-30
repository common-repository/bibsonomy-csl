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
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Util\ParameterCheck;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Builds URL for GetPopularPosts REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Sebastian Böttger
 * @author Florian Fassing
 */
class GetPopularPostsQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /posts/popular ?resourcetype=(bibtexǀbookmark)
     *
     * @param string $resourceType the requested resourcetype
     * @param string $grouping determines to which group $groupingName belongs
     * @param string $groupingName name of user or group who/which created the posts
     * @param int $start start index
     * @param int $end end index
     * @throws UnsupportedOperationException
     */
    public function __construct(string $resourceType, string $grouping, string $groupingName, int $start, int $end)
    {
        parent::__construct();

        ParameterCheck::checkGrouping($grouping);
        ParameterCheck::checkResourcetype($resourceType);

        // Check whether start and end params make sense.
        if ($start < 0) {
            $start = 0;
        }
        if ($end < $start) {
            $end = $start;
        }

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::POSTS_URL,
                RESTConfig::POSTS_POPULAR_SUB_PATH
            ],
            [// params
                RESTConfig::RESOURCE_TYPE_PARAM => $resourceType,
                $grouping => $groupingName,
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
        $this->response = $client->get($this->url);
        $this->executed = true;

        return $this;
    }
}

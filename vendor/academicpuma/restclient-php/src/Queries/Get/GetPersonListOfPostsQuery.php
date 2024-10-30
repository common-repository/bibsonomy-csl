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

/**
 * Class GetPersonListOfPostsQuery
 * @package AcademicPuma\RestClient\Queries\Get
 * @author kchoong
 */
class GetPersonListOfPostsQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     * The query is to get the posts of a single person specified by their person id.
     *
     * GET /persons/[personid]/posts
     *
     * @param string $personId
     * @param int $start
     * @param int $end
     */
    public function __construct(string $personId, int $start = 0, int $end = 20)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::PERSONS_URL,
                $personId,
                RESTConfig::POSTS_URL
            ],
            [// params
                RESTConfig::START_PARAM => $start,
                RESTConfig::END_PARAM => $end
            ]
        );
    }

    /**
     * Execute a prepared query.
     *
     * @param Client $httpClient
     * @param array $reqOpts request options
     *
     * @return $this
     */
    public function execute(Client $httpClient, array $reqOpts = [])
    {

        $this->response = $httpClient->get($this->url, $reqOpts);
        $this->executed = true;

        return $this;
    }

}
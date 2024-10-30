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

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\ResourceType;
use AcademicPuma\RestClient\Config\TagStatus;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Util\ParameterCheck;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 * Builds URL for GetConcepts REST-API calls.
 * Also holds the response after being executed.
 *
 * Use this Class to get concepts
 * 1) from all users
 * 2) from a specified group or
 * 3) from a specified user
 *
 * @author Florian Fassing
 */
class GetConceptsQuery extends AbstractQuery
{

    /**
     *
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /users/[username]/concepts ?status=(allǀpicked)
     *
     * @param string $resourceType the requested resourcetype
     * @param string $grouping determines to which group $groupingName belongs
     * @param string $groupingName name of user or group who/which created the concept
     * @param array $tags a list of tags which shall be part of the relations
     * @param string|null $regex a regex to possibly filter the relatons retrieved
     * @param string|null $status the conceptstatus, i.e. all, picked or unpicked
     * @param int $start start index
     * @param int $end end index
     *
     * @throws UnsupportedOperationException
     */
    public function __construct(string $resourceType, string $grouping, string $groupingName, array $tags,
                                ?string $regex, ?string $status, int $start, int $end)
    {
        parent::__construct();

        // Check whether start and end params make sense.
        if ($start < 0) {
            $start = 0;
        }
        if ($end < $start) {
            $end = $start;
        }

        if ($status === TagStatus::PICKED) {
            ParameterCheck::checkTags($tags);
        }

        //Util\ParameterCheck::checkGroupingWithoutGroups($grouping);
        ParameterCheck::checkResourcetype($resourceType);

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                $grouping,
                $groupingName,
                Config\RESTConfig::CONCEPTS_URL
            ],
            [// params
                //TODO: ? $regex,
                Config\RESTConfig::TAGS_PARAM => $tags,
                Config\RESTConfig::CONCEPT_STATUS_PARAM => $status,
                Config\RESTConfig::START_PARAM => $start,
                Config\RESTConfig::END_PARAM => $end
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

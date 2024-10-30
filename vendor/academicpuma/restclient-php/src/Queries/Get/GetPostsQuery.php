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
 * Builds URL for GetPosts REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Sebastian Böttger
 * @author Florian Fassing
 */
class GetPostsQuery extends AbstractQuery
{
    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /users/[username]/posts ?tags=[t1+t2+...+tn] ?resourcetype=(bibtexǀbookmark)
     *
     * @param string $resourceType the requested resourcetype
     * @param string $grouping determines to which group $groupingName belongs
     * @param string $groupingName name of user or group who/which created the posts
     * @param array $tags a list of tags filtering the posts
     * @param string|null $hash interhash of resource
     * @param string|null $search full string search currently not implemented
     * @param array $sortKeys a list of keys to sort the posts by
     * @param array $sortOrders a list of sort orders to set the order for the keys
     * @param string $searchType (local|searchindex)
     * @param int $start start index
     * @param int $end end index
     * @param string $format (xml|json|csl|bibtex|endnote)
     * @throws UnsupportedOperationException
     */
    public function __construct(string  $resourceType,
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
                                string  $format = 'xml')
    {
        parent::__construct();

        // Check whether start and end params make sense.
        if (is_numeric($start) && $start < 0) {
            $start = 0;
        }
        if (is_numeric($end) && $end < $start) {
            $end = $start;
        }

        ParameterCheck::checkGrouping($grouping);
        ParameterCheck::checkResourcetype($resourceType);

        $this->url = $this->urlBuilder->buildUrl(
            [RESTConfig::POSTS_URL], // path
            [ // params
                RESTConfig::RESOURCE_TYPE_PARAM => $resourceType,
                $grouping => $groupingName,
                RESTConfig::TAGS_PARAM => $tags,
                RESTConfig::RESOURCE_PARAM => $hash,
                RESTConfig::SEARCH_PARAM => $search,
                RESTConfig::SORTKEY_PARAM => $sortKeys,
                RESTConfig::SORTORDER_PARAM => $sortOrders,
                RESTConfig::START_PARAM => $start,
                RESTConfig::END_PARAM => $end,
                RESTConfig::FORMAT_PARAM => $format,
                RESTConfig::SEARCHTYPE_PARAM => $searchType
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

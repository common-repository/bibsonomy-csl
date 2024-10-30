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
use AcademicPuma\RestClient\Config\TagOrder;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Util;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Builds URL for GetTagRelation REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class GetTagRelationQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /tags/[tag] ?relation=(relatedǀsimilarǀsubtagsǀsupertags)
     *
     * @param string $grouping
     * @param string $groupingName
     * @param string $relation can be on of the following: see above
     * @param array $tags
     * @param string $order
     * @param int $start start index
     * @param int $limit
     *
     * @throws UnsupportedOperationException
     */
    public function __construct(string $grouping,
                                string $groupingName,
                                string $relation,
                                array $tags = [],
                                string $order = TagOrder::ALPHANUMERIC,
                                int $start = 0,
                                int $limit = 20)
    {
        parent::__construct();

        // Default relation is 'related'.
        if ($relation !== RESTConfig::RELATED_TAG_RELATION &&
            $relation !== RESTConfig::SIMILAR_TAG_RELATION) {
            throw new UnsupportedOperationException("Relation method $relation is not supported");
        }

        Util\ParameterCheck::checkGrouping($grouping);
        Util\ParameterCheck::checkTagOrder($order);

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::TAGS_URL,
                $tags
            ],
            [ // params
                $grouping => $groupingName,
                RESTConfig::TAG_REL_PARAM => $relation,
                RESTConfig::ORDER_PARAM => $order,
                RESTConfig::START_PARAM => $start,
                RESTConfig::END_PARAM => $limit
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

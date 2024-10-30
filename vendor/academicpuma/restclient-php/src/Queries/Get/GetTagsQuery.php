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
 * Builds URL for GetTags REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class GetTagsQuery extends AbstractQuery
{
    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /tags ?filter=[regex] ?(userǀgroupǀviewable)=[usernameǀgroupname] ?order=(frequencyǀalph)
     *
     * @param string $grouping determines to which group $groupingName belongs
     * @param string $groupingName name of user or group who/which created the tags
     * @param string|null $regex a regular expression used to filter the tagnames
     * @param string|null $order (frequency|alph)
     * @param int $start start index
     * @param int $end end index
     * @throws UnsupportedOperationException
     */
    public function __construct(string $grouping, string $groupingName, ?string $regex, ?string $order, int $start, int $end)
    {
        parent::__construct();

        // Check wether start and end params make sense.
        if ($start < 0) {
            $start = 0;
        }
        if ($end < $start) {
            $end = $start;
        }

        ParameterCheck::checkGrouping($grouping);

        $this->url = $this->urlBuilder->buildUrl(
            [RESTConfig::TAGS_URL], // path
            [ // params
                RESTConfig::FILTER_PARAM => $regex,
                $grouping => $groupingName,
                RESTConfig::ORDER_PARAM => $order,
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

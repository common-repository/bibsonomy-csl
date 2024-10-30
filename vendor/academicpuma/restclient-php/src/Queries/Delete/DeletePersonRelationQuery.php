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

namespace AcademicPuma\RestClient\Queries\Delete;


use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Query for a request to delete a relation of a PersonUtils
 * @package AcademicPuma\RestClient\Queries\Delete
 * @author kchoong
 */
class DeletePersonRelationQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     * The query is to delete a relation of a single person specified by their person id
     * and the relationtype and index of the relation.
     *
     * DELETE /persons/[personid]/relations/[interhash]/[type]/[index]
     *
     * @param string $personId
     * @param string $resourceHash
     * @param string $type
     * @param string $index
     */
    public function __construct(string $personId, string $resourceHash, string $type, string $index)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::PERSONS_URL,
                $personId,
                RESTConfig::RELATIONS_URL,
                $resourceHash,
                $type,
                $index
            ],
            [] // no params
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
        $this->response = $client->delete($this->url, $reqOpts);
        $this->executed = true;

        return $this;
    }

}
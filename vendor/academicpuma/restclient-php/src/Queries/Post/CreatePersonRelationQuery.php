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

namespace AcademicPuma\RestClient\Queries\Post;

use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\ResourcePersonRelation;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Renderer\XMLModelRenderer;
use DOMException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Query for a request to create a new resource relation of a PersonUtils
 * @package AcademicPuma\RestClient\Queries\Post
 * @author
 */
class CreatePersonRelationQuery extends AbstractQuery
{
    private $resourceHash;

    /**
     * The query constructor builds the url and saves it into the url property.
     * The query is to create a new resource relation for a single person specified by their person id.
     *
     * POST /persons/[personid]/relations
     *
     * @param string $personId
     * @param ResourcePersonRelation $relation
     * @throws DOMException
     */
    public function __construct(string $personId, ResourcePersonRelation $relation)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::PERSONS_URL,
                $personId,
                RESTConfig::RELATIONS_URL
            ],
            [] // no params
        );

        // Render XML from model and write to body.
        $xmlRenderer = new XMLModelRenderer();
        $this->body = $xmlRenderer->render($relation);
    }

    /**
     * Execute a prepared query.
     *
     * @param Client $client
     * @param array $reqOpts request options
     *
     * @return $this
     * @throws UnsupportedOperationException
     * @throws GuzzleException
     */
    public function execute(Client $client, array $reqOpts = []): AbstractQuery
    {
        $reqOpts['body'] = $this->body;
        $reqOpts['headers'] = ['Content-Type' => 'application/xml'];

        $this->response = $client->post($this->url, $reqOpts);
        $this->executed = true;

        // Get resourceHash from response.
        $this->resourceHash = (string)$this->getResponseXML()->resourcehash;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceHash(): string
    {
        return $this->resourceHash;
    }

}
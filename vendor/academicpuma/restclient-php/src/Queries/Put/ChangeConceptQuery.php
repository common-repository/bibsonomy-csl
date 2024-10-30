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

namespace AcademicPuma\RestClient\Queries\Put;

use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Renderer\XMLModelRenderer;
use DOMException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use LogicException;

/**
 * Builds URL for ChangeConcept REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class ChangeConceptQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * PUT /users/[username]/concepts/[conceptname]
     *
     * @param Tag $concept the concept which will be edited
     * @param string $username name of the user holding the concept
     * @param string $operation TODO: description
     * @throws DOMException
     */
    public function __construct(Tag $concept, string $username, string $operation)
    {

        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $username,
                RESTConfig::CONCEPTS_URL,
                $concept->getName()
            ],
            [] // no params
        );

        // Render XML from model and write to body.
        $xmlRenderer = new XMLModelRenderer();
        $this->body = $xmlRenderer->render($concept);
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

        $reqOpts['body'] = $this->body;
        $reqOpts['headers'] = ['Content-Type' => 'application/xml'];

        try {
            $this->response = $client->put($this->url, $reqOpts);
        } catch (RequestException|LogicException $e) {
            echo $e->getMessage();
        }

        $this->executed = true;
        return $this;
    }
}

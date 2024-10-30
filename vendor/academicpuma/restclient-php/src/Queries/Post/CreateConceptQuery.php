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
use AcademicPuma\RestClient\Model\Tag;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Renderer\XMLModelRenderer;
use DOMException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Builds URL for CreateConcept REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class CreateConceptQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * POST /users/[username]/concepts/[conceptname]
     *
     * @param Tag $concept the concept which will be created
     * @param string $userName name of the user who will hold the created concept
     * @throws DOMException
     */
    public function __construct(Tag $concept, string $userName)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $userName,
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

        $this->response = $client->post($this->url, $reqOpts);
        $this->executed = true;

        return $this;
    }
}
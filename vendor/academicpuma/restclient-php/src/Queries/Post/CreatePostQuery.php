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
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Renderer\XMLModelRenderer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Builds URL for CreatePost REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class CreatePostQuery extends AbstractQuery
{
    private $resourceHash;

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * POST /users/[username]/posts
     *
     * @param Post $post the post which will be created
     * @param string $username name of the user which will hold the post
     * @throws \DOMException
     */
    public function __construct(Post $post, string $username)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $username,
                RESTConfig::POSTS_URL
            ],
            [] // no params
        );

        // Render XML from model and write to body.
        $xmlRenderer = new XMLModelRenderer();
        $this->body = $xmlRenderer->render($post);
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

    /**
     * @param string $resourceHash
     */
    public function setResourceHash(string $resourceHash)
    {
        $this->resourceHash = $resourceHash;
    }
}

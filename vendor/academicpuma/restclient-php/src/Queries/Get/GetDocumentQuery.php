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

use AcademicPuma\RestClient\Config\DocumentType;
use AcademicPuma\RestClient\Config\RESTConfig;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Builds URL for GetDocument REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class GetDocumentQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * GET /users/[username]/posts/[resourcehash]/documents/[fileName]
     *
     * @param string $userName name of the user holding the post
     * @param string $resourceHash interhash of resource
     * @param string $fileName name of the file which will be received
     * @param string $type document type
     */
    public function __construct(string $userName, string $resourceHash, string $fileName, string $type = DocumentType::FILE)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $userName,
                RESTConfig::POSTS_URL,
                $resourceHash,
                RESTConfig::DOCUMENTS_SUB_PATH, $fileName
            ],
            // params
            ($type === DocumentType::FILE) ? [] : ['preview' => $type]
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

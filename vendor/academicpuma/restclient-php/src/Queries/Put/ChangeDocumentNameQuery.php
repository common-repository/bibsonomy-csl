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
use AcademicPuma\RestClient\Model\Document;
use AcademicPuma\RestClient\Queries\AbstractQuery;
use AcademicPuma\RestClient\Renderer\XMLModelRenderer;
use DOMException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ChangeDocumentNameQuery extends AbstractQuery
{

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * PUT /users/[username]/posts/[resourcehash]/documents/[fileName]
     *
     * @param string $userName
     * @param string $resourceHash the intraHash of the post the document belongs to
     * @param string $fileName new name for the file
     * @param Document $document
     *
     * @throws DOMException
     */
    public function __construct(string $userName, string $resourceHash, string $fileName, Document $document)
    {

        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $userName,
                RESTConfig::POSTS_URL,
                $resourceHash,
                RESTConfig::DOCUMENTS_SUB_PATH,
                $fileName
            ],
            [] // no params
        );

        // Render XML from model and write to body.
        $xmlRenderer = new XMLModelRenderer();
        $this->body = $xmlRenderer->render($document);
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

        $this->response = $client->put($this->url, $reqOpts);
        $this->executed = true;

        return $this;
    }
}

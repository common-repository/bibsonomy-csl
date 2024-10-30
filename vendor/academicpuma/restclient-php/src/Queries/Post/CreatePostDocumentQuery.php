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
use AcademicPuma\RestClient\Queries\AbstractQuery;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Builds URL for CreateDocument REST-API calls.
 * Also holds the response after being executed.
 *
 * @author Florian Fassing
 */
class CreatePostDocumentQuery extends AbstractQuery
{

    private $fileName;
    private $filePath;

    /**
     * The query constructor builds the url and saves it into the url property.
     *
     * POST /users/[username]/posts/[resourcehash]/documents/
     *
     * @param string $userName name of the user holding the post
     * @param string $resourceHash interhash of the post the document will be attached to
     * @param string $fileName name of the file
     * @param string $filePath local path to file which will be uploaded
     */
    public function __construct(string $userName, string $resourceHash, string $fileName, string $filePath)
    {
        parent::__construct();

        $this->url = $this->urlBuilder->buildUrl(
            [// path
                RESTConfig::USERS_URL,
                $userName,
                RESTConfig::POSTS_URL,
                $resourceHash,
                RESTConfig::DOCUMENTS_SUB_PATH
            ],
            [] // no params
        );

        $this->fileName = $fileName;
        $this->filePath = $filePath;
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

        $fileinfo = array(
            'name' => $this->fileName,
            'clientNumber' => 'lorem',
            'type' => 'ipsum',
        );

        $res = $client->request('POST', $this->url, [
            'multipart' => [
                [
                    'name' => 'FileContents',
                    'contents' => file_get_contents($this->filePath),
                    'filename' => $this->fileName
                ],
                [
                    'name' => 'FileInfo',
                    'contents' => json_encode($fileinfo)
                ]
            ],
            'debug' => true
        ]);


        $response = json_decode($res->getBody());

        var_dump($response);

        return $this;
    }
}

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

namespace AcademicPuma\RestClient\Accessor;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

/**
 * The BasicAuthAccessor class holds information about the
 * connection for the RestClient. I.e. username and api-key
 * for basic authentication.
 *
 * @author Florian Fassing
 */
class BasicAuthAccessor extends Accessor
{

//     private $baseUrl; 
//     private $username; 
//     private $apikey;

    /**
     *
     * @param string $baseUrl
     * @param string $username
     * @param string $apikey
     */
    public function __construct($baseUrl, $username, $apikey)
    {

        parent::__construct();
        $stack = HandlerStack::create();

        // Create new Guzzle Client.
        $this->client = new Client([
            'base_uri' => rtrim($baseUrl, '/'),
            //'base_uri' => $baseUrl,
            'auth' => [$username, $apikey, 'basic'],
            'handler' => $stack
        ]);
    }
}

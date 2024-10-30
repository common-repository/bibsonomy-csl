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

use AcademicPuma\OAuth\OAuthAdapter;
use AcademicPuma\OAuth\Token\AccessToken;

/**
 * OAuthAccessor is holding a http client and the necessary OAuth information for the RESTClient to make requests.
 *
 * @author Florian Fassing
 */
class OAuthAccessor extends Accessor
{

    /**
     * Check this link for detailed OAuth explanation:
     * https://github.com/Mashape/mashape-oauth/blob/master/FLOWS.md#oauth-10a-three-legged
     *
     * @param string $baseUrl
     * @param AccessToken $accessToken
     * @param string $consumer_key
     * @param string $consumer_secret
     * @param string $authUrl Will be appended to the base url in order to send the user there.
     */
    public function __construct($baseUrl, AccessToken $accessToken, $consumer_key, $consumer_secret, $authUrl = 'oauth/authorize')
    {

        parent::__construct();

        $oAuthAdapter = new OAuthAdapter(array("baseUrl" => rtrim($baseUrl, '/') . '/', "consumerKey" => $consumer_key,
            "consumerSecret" => $consumer_secret, 'authUrl' => $authUrl));

        $this->client = $oAuthAdapter->prepareClientForOAuthRequests($accessToken);
    }
}

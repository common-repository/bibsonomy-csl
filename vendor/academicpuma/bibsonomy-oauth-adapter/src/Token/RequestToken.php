<?php
/* 
    Copyright (C) 2015 - Sebastian Böttger <boettger@cs.uni-kassel.de>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace AcademicPuma\OAuth\Token;

use Psr\Http\Message\ResponseInterface;

/**
 *
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class RequestToken implements TokenInterface {

    /**
     *
     * @var string token
     */
    private $oauthToken;

    /**
     *
     * @var string token secret
     */
    private $oauthTokenSecret;

    /**
     *
     * @var bool is callback confirmed?
     */
    private $oauthCallbackConfirmed;

    /**
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response) {

        $params = array();
        parse_str((string) $response->getBody(), $params);

        $validParams = array_key_exists('oauth_token', $params)
                    && array_key_exists('oauth_token_secret', $params)
                    && array_key_exists('oauth_callback_confirmed', $params);

        if (!$validParams) {
            throw new \BadMethodCallException("Invalid params");
        }

        $this->oauthToken = (string) $params['oauth_token'];
        $this->oauthTokenSecret = (string) $params['oauth_token_secret'];
        $this->oauthCallbackConfirmed = (bool) $params['oauth_callback_confirmed'];
    }

    /**
     *
     * @return string
     */
    public function getOauthToken() {

        return $this->oauthToken;
    }

    /**
     *
     * @return string
     */
    public function getOauthTokenSecret() {

        return $this->oauthTokenSecret;
    }

    /**
     * @return bool
     */
    public function isOauthCallbackConfirmed() {

        return $this->oauthCallbackConfirmed;
    }

}

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
 * This class represents the AccessToken
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class AccessToken implements TokenInterface {

    private $oauthToken;

    private $oauthTokenSecret;

    private $userId;

    public function __construct(ResponseInterface $response = null) {

        if ($response !== null) {
            $params = array();
            parse_str((string) $response->getBody(), $params);

            $validParams = array_key_exists('oauth_token', $params)
                        && array_key_exists('oauth_token_secret', $params)
                        && array_key_exists('user_id', $params);

            if (!$validParams) {
                throw new \BadMethodCallException("Invalid params");
            }

            $this->oauthToken = $params['oauth_token'];
            $this->oauthTokenSecret = $params['oauth_token_secret'];
            $this->userId = $params['user_id'];
        }
    }

    public function getOauthToken() {

        return $this->oauthToken;
    }

    public function getOauthTokenSecret() {

        return $this->oauthTokenSecret;
    }

    public function getUserId() {

        return $this->userId;
    }

    public function setOauthToken($oauthToken) {

        $this->oauthToken = $oauthToken;
    }

    public function setOauthTokenSecret($oauthTokenSecret) {

        $this->oauthTokenSecret = $oauthTokenSecret;
    }

    public function setUserId($userId) {

        $this->userId = $userId;
    }
}

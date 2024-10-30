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

namespace AcademicPuma\RestClient\Authentication\OAuth\Subscriber;

use GuzzleHttp\Subscriber\Oauth\Oauth1;
use AcademicPuma\RestClient\Authentication\OAuth\Token\ConsumerToken;
use AcademicPuma\RestClient\Authentication\OAuth\Token\AccessToken;
use AcademicPuma\RestClient\Authentication\OAuth\Token\RequestToken;

/**
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class BibSonomySubscriber extends Oauth1 {

    /**
     *
     * @var ConsumerToken
     */
    protected $consumerToken;

    /**
     *
     * @param ConsumerToken $consumerToken
     */
    public function __construct(ConsumerToken $consumerToken) {

        parent::__construct([]);
        $this->consumerToken = $consumerToken;
    }

    /**
     *
     * @return Oauth1
     */
    public function getRequestTokenSubscriber(): Oauth1
    {
        return new parent([
                            'consumer_key' => $this->consumerToken->getOauthToken(),
                            'consumer_secret' => $this->consumerToken->getOauthTokenSecret()]);
    }

    /**
     *
     * @param RequestToken $requestToken
     *
     * @return Oauth1
     */
    public function getAccessTokenSubscriber(RequestToken $requestToken): Oauth1
    {
        return new parent([
                            'consumer_key' => $this->consumerToken->getOauthToken(),
                            'consumer_secret' => $this->consumerToken->getOauthTokenSecret(),
                            'token' => $requestToken->getOauthToken(),
                            'token_secret' => $requestToken->getOauthTokenSecret()]);
    }

    /**
     *
     * @param AccessToken $accessToken
     *
     * @return Oauth1
     */
    public function getOAuthSubscriber(AccessToken $accessToken): Oauth1
    {
        return new parent([
                            'consumer_key' => $this->consumerToken->getOauthToken(),
                            'consumer_secret' => $this->consumerToken->getOauthTokenSecret(),
                            'token' => $accessToken->getOauthToken(),
                            'token_secret' => $accessToken->getOauthTokenSecret()]);
    }
}

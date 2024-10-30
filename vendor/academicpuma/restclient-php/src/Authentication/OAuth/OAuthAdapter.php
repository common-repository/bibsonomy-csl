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

namespace AcademicPuma\RestClient\Authentication\OAuth;

use AcademicPuma\RestClient\Authentication\OAuth\Subscriber\BibSonomySubscriber;
use AcademicPuma\RestClient\Authentication\OAuth\Token\AccessToken;
use AcademicPuma\RestClient\Authentication\OAuth\Token\ConsumerToken;
use AcademicPuma\RestClient\Authentication\OAuth\Token\RequestToken;
use BadMethodCallException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;

/**
 * OAuth Adapter for PUMA and BibSonomy.
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class OAuthAdapter
{
    const REQUEST_TOKEN_URL = 'oauth/requestToken';
    const ACCESS_TOKEN_URL = 'oauth/accessToken';
    const CLIENT_METHODS = ['get', 'post', 'put', 'delete', 'head', 'options', 'patch'];

    protected $client;

    protected $consumerToken;

    protected $bibsonomySubscriber;

    protected $config;

    protected $authUrl = 'oauth/authorize';

    protected $_parameters;

    /**
     *
     * @param array $config <code>['consumerKey' => '','consumerSecret => '', 'callbackUrl' => '', 'baseUrl' => '']</code>
     */
    public function __construct(array $config = [])
    {

        $this->config = $config;

        $this->client = new Client([
            'base_uri' => $this->addTrailingSlash($config['baseUrl']),
            'auth' => 'oauth'
        ]);

        if (!empty($config['authUrl'])) $this->authUrl = $this->removeTrailingAndLeadingSlash($config['authUrl']);

        $this->consumerToken = new ConsumerToken($config['consumerKey'], $config['consumerSecret']);

        $this->bibsonomySubscriber = new BibSonomySubscriber($this->consumerToken);
    }

    /**
     *
     * @return ConsumerToken
     */
    public function getConsumerToken(): ConsumerToken
    {
        return $this->consumerToken;
    }

    /**
     *
     * @return RequestToken
     * @throws GuzzleException
     */
    public function getRequestToken(): RequestToken
    {
        $middleware = $this->bibsonomySubscriber->getRequestTokenSubscriber();
        $stack = HandlerStack::create();
        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => $this->config['baseUrl'] . 'api',
            //'defaults' => ['auth' => 'oauth'],
            'auth' => 'oauth',
            'handler' => $stack,
        ]);

        $res = $this->client->post(self::REQUEST_TOKEN_URL, ['form_params' => ['oauth_callback' => $this->config['callbackUrl']]]);

        return new RequestToken($res);
    }

    /**
     *
     * @param RequestToken $requestToken
     *
     * @return AccessToken
     * @throws GuzzleException
     */
    public function getAccessToken(RequestToken $requestToken): AccessToken
    {

        $authToken = filter_input(INPUT_GET, 'oauth_token', FILTER_SANITIZE_STRING);
        $userId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

        if ($requestToken->getOauthToken() === $authToken) {
            $middleware = $this->bibsonomySubscriber->getAccessTokenSubscriber($requestToken);
            $stack = HandlerStack::create();
            $stack->push($middleware);

            $this->client = new Client([
                'base_uri' => $this->config['baseUrl'] . 'api',
                //'defaults' => ['auth' => 'oauth'],
                'auth' => 'oauth',
                'handler' => $stack,
            ]);

            $res = $this->client->post(self::ACCESS_TOKEN_URL, ['form_params' => ['user_id' => $userId]]);

            $accessToken = new AccessToken($res);

            $this->client = new Client([
                'base_uri' => $this->config['baseUrl'] . 'api',
                'auth' => 'oauth',
            ]);

            return $accessToken;
        }

        throw new InvalidArgumentException("Error: The oauth_token from callback is not the same as the request_token");
    }

    /**
     *
     * @param RequestToken $requestToken
     */
    public function redirect(RequestToken $requestToken)
    {

        $params_ = $this->assembleRedirectParams($requestToken);
        $encodedParams = array();
        foreach ($params_ as $key => $value) {
            $encodedParams[] = self::urlEncode($key)
                . '='
                . self::urlEncode($value);
        }
        $params = implode('&', $encodedParams);

        header('Location: ' . $this->buildAuthorizeUrl($params));
    }

    private function buildAuthorizeUrl($params): string
    {
        return $this->config['baseUrl'] . $this->authUrl . '?' . $params;
    }

    private function assembleRedirectParams(RequestToken $requestToken): array
    {
        $params = array(
            'oauth_token' => $requestToken->getOauthToken()
        );

        $params['oauth_callback'] = $this->config['callbackUrl'];

        if (!empty($this->_parameters)) {
            $params = array_merge($params, $this->_parameters);
        }

        return $params;
    }

    /**
     * Attaches $accessToken to the emitter
     *
     * @param AccessToken $accessToken
     * @param string $proxy
     *
     * @return Client
     */
    public function prepareClientForOAuthRequests(AccessToken $accessToken, string $proxy = ''): Client
    {
        $middleware = $this->bibsonomySubscriber->getOAuthSubscriber($accessToken);
        $stack = HandlerStack::create();
        $stack->push($middleware);

        $this->client = new Client([
            'base_uri' => $this->config['baseUrl'] . 'api',
            'auth' => 'oauth',
            'handler' => $stack,
            'proxy' => $proxy,
        ]);

        return $this->client;
    }

    public function __call($method, array $args = [])
    {

        if (!in_array($method, self::CLIENT_METHODS)) {
            throw new BadMethodCallException("Method $method does not exist on " . __CLASS__);
        }

        $url = isset($args[0]) ? $args[0] : null;
        $options = isset($args[1]) ? $args[1] : [];

        // trigger request
        return $this->client->$method($url, $options);
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public static function urlEncode(string $value): string
    {
        $encoded_ = rawurlencode($value);
        return str_replace('%7E', '~', $encoded_);
    }

    /**
     * Makes sure the url to validate has a trailing slash.
     *
     * @param string $urlToValidate The url to validate.
     * @return string The passed url with a trailing slash.
     */
    private function addTrailingSlash(string $urlToValidate): string
    {
        return rtrim($urlToValidate, '/') . '/';
    }

    private function removeTrailingAndLeadingSlash(string $urlToValidate): string
    {
        return ltrim(rtrim($urlToValidate, '/'), '/');
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}

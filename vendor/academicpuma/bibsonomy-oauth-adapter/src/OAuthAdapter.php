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

namespace AcademicPuma\OAuth;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

/**
 * 
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class OAuthAdapter {


    const REQUEST_TOKEN_URL = 'oauth/requestToken';
    
    const ACCESS_TOKEN_URL = 'oauth/accessToken';

    /**
     * BibSonomy default is 'oauth/authorize'
     * @var string
     */
    public $authorizeURL = 'oauth/authorize';
    
    
    public static $CLIENT_METHODS = ['get', 'post', 'put', 'delete', 'head', 'options', 'patch'];
    
    /**
     *
     * @var array
     */
    protected $config;
    
    /**
     *
     * @var Token\ConsumerToken
     */
    protected $consumerToken;
    
    /**
     *
     * @var \GuzzleHttp\Client 
     */
    protected $client;
    
    /**
     *
     * @var \AcademicPuma\OAuth\Subscriber\BibSonomySubscriber 
     */
    protected $bibsonomySubscriber;
    
    /**
     * 
     * @var array
     */
    protected $_parameters;
    
    /**
     * 
     * @param array $config <code>['consumerKey' => '','consumerSecret => '', 'callbackUrl' => '', 'baseUrl' => '']</code>
     */
    public function __construct(array $config = []) {
        
        $this->config = $config;
        
        $this->client = new Client([
            'base_uri' => $this->addTrailingSlash($config['baseUrl']),
            'auth' => 'oauth'
        ]);

        if (!empty($config['authUrl'])) $this->authorizeURL = $this->removeTrailingAndLeadingSlash($config['authUrl']);
        
        $this->consumerToken = new Token\ConsumerToken($config['consumerKey'], $config['consumerSecret']);
        
        $this->bibsonomySubscriber = new Subscriber\BibSonomySubscriber($this->consumerToken);
    }
    
    /**
     * 
     * @return \AcademicPuma\OAuth\Token\ConsumerToken
     */
    public function getConsumerToken() {
        
        return $this->consumerToken;
    }
    
    /**
     * 
     * @return \AcademicPuma\OAuth\Token\RequestToken
     */
    public function getRequestToken() {
        
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
        
        return new Token\RequestToken($res);
    }

    /**
     *
     * @param \AcademicPuma\OAuth\Token\RequestToken $requestToken
     *
     * @return Token\AccessToken
     */
    public function getAccessToken(Token\RequestToken $requestToken) {
        
        $authToken = filter_input(INPUT_GET, 'oauth_token', FILTER_SANITIZE_STRING);
        $userId    = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
        
        if($requestToken->getOauthToken() === $authToken) {
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
            
            $accessToken = new Token\AccessToken($res);
            
            $this->client = new Client([
                'base_uri' => $this->config['baseUrl'] . 'api',
                'auth' => 'oauth',
            ]);
            
            return $accessToken;
        }
        
        throw new \InvalidArgumentException("Error: The oauth_token from callback is not the same as the request_token");
    }
    
    /**
     * 
     * @param \AcademicPuma\OAuth\Token\RequestToken $requestToken
     */
    public function redirect(Token\RequestToken $requestToken) {
            
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
    
    private function buildAuthorizeUrl($params) {
        
        return $this->config['baseUrl'] . $this->authorizeURL . '?' . $params;
    }
    
    private function assembleRedirectParams(Token\RequestToken $requestToken) {
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
     * @param \AcademicPuma\OAuth\Token\AccessToken $accessToken
     *
     * @return Client
     */
    public function prepareClientForOAuthRequests(Token\AccessToken $accessToken) {       
        
        $middleware = $this->bibsonomySubscriber->getOAuthSubscriber($accessToken);
        $stack = HandlerStack::create();
        $stack->push($middleware);
        
        $this->client = new Client([
            'base_uri' => $this->config['baseUrl'] . 'api',
            'auth' => 'oauth',
            'handler' => $stack,
        ]);
        
        return $this->client;  
    }
    
    public function __call($method, array $args = []) {
        
        if (!in_array($method, self::$CLIENT_METHODS)) {
            throw new \BadMethodCallException("Method $method does not exist on " . __CLASS__);
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
    public static function urlEncode($value){
        
        $encoded_ = rawurlencode($value);
        $encoded = str_replace('%7E', '~', $encoded_);
        return $encoded;
    }

    /**
     * Makes sure the url to validate has a trailing slash.
     *
     * @param string $urlToValidate The url to validate.
     * @return string The passed url with a trailing slash.
     */
    private function addTrailingSlash($urlToValidate) {
        return rtrim($urlToValidate, '/') . '/';
    }

    private function removeTrailingAndLeadingSlash($urlToValidate) {
        return ltrim(rtrim($urlToValidate, '/'), '/');
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient() {
        return $this->client;
    }
}

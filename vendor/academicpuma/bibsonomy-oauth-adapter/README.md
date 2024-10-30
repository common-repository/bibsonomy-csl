# OAuthAdapter for PUMA and BibSonomy #

OAuthAdapter is a service library which uses [Guzzle HTTP client](https://github.com/guzzle/guzzle) in order to 
get access to the [PUMA](http://www.academic-puma.de)/[BibSonomy](http://www.bibsonomy.org) API via OAuth1. In 
Addition, OAuthAdapter helps you to perform a token exchange.

## Understanding OAuth ##

BibSonomy and PUMA support OAuth 1.0a mechanism, also called three-legged OAuth. Actually, it's not necessary to know how it works exactly, but it's good to know some basics about it to prevent failures in usage of OAuth.

A helpful overview you can find at [https://github.com/Mashape/mashape-oauth/blob/master/FLOWS.md#oauth-10a-three-legged](https://github.com/Mashape/mashape-oauth/blob/master/FLOWS.md#oauth-10a-three-legged).

## Installation ##

Use composer to add OAuthAdapter to your PHP project.

```
$ composer require academicpuma/oauthadapter
$ composer update
```

## How to use OAuthAdapter ##

**Include the autoloader**
```
<?php
$projectPath = 'projectpath';
include $projectPath.'vendor/autoload.php';
include $projectPath.'config.php';
?>
```

**Initialize the OAuthAdapter**
```
<?php
use AcademicPuma\OAuth\OAuthAdapter;
$client = new OAuthAdapter([
    'consumerKey'       => CONSUMER_KEY,
    'consumerSecret'    => CONSUMER_SECRET,
    'callbackUrl'       => CALLBACK_URL,
    'baseUrl'           => BASE_URL
]);
?>
```

**Fetch the Request Token and redirect**
```
<?php
try {
    $requestToken = $client->getRequestToken(); //get Request Token
    session_start();
    $_SESSION['REQUEST_TOKEN'] = serialize($requestToken); //save Request Token in the session
    
} catch(\Exception $e) {
    //do something
}
$client->redirect($requestToken); //redirect to PUMA/BibSonomy to verify user authorization
?>
```

**Create the callback script that is called by PUMA/BibSonomy after obtaining user authorization**
```
<?php

include $projectPath.'vendor/autoload.php';
include $projectPath.'config.php';

use AcademicPuma\OAuth\OAuthAdapter;

session_start(); //resume session

// Get the RequestToken from session
$requestToken = unserialize($_SESSION['REQUEST_TOKEN']); 

$client = new OAuthAdapter([
    'consumerKey'       => CONSUMER_KEY,
    'consumerSecret'    => CONSUMER_SECRET,
    'callbackUrl'       => CALLBACK_URL,
    'baseUrl'           => BASE_URL
]);

$accessToken = $client->getAccessToken($requestToken); //fetch Access Token

//persist the Access Token 
$_SESSION['ACCESS_TOKEN'] = serialize($accessToken); //better: save it into a database

```

**Use the OAuthAdapter to request the API**
```
<?php
include $projectPath.'vendor/autoload.php';
include $projectPath.'config.php';

use AcademicPuma\OAuth\OAuthAdapter;

session_start(); //resume session

// Get the Access Token from session
$accessToken = unserialize($_SESSION['ACCESS_TOKEN']); 

/**
 * @var AcademicPuma\OAuth\OAuthAdapter
 */
$adapter = new OAuthAdapter([
    'consumerKey'       => CONSUMER_KEY,
    'consumerSecret'    => CONSUMER_SECRET,
    'callbackUrl'       => CALLBACK_URL,
    'baseUrl'           => BASE_URL
]);

$adapter->prepareClientForOAuthRequests($accessToken); //attach accessToken
$jsonString = $adapter->get(BASE_URL.'api/groups?format=json')->json();
$object = json_decode($jsonString);
var_dump($object);
?>
```
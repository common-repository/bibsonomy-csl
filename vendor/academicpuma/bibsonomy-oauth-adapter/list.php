<?php

include('vendor/autoload.php');
include('config.php');

use AcademicPuma\OAuth\OAuthAdapter;

session_start(); //resume session

// Get the RequestToken from session
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

$client = $adapter->prepareClientForOAuthRequests($accessToken);

//method 1: using Guzzle Client
$json1 = $client->get(BASE_URL.'api/users/'.$accessToken->getUserId().'?format=json')->json();

echo "<pre>";
print_r($json1);
echo "</pre>";

//method 2: using the adapter
$json2 = $adapter->get(BASE_URL.'api/groups?format=json')->json();

echo "<pre>";
print_r($json2);
echo "</pre>";
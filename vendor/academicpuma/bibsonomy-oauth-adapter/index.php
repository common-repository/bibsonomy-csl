<?php
error_reporting(E_ALL);

include 'vendor/autoload.php';
include 'config.php';

use AcademicPuma\OAuth\OAuthAdapter;

$client = new OAuthAdapter([
    'consumerKey'       => CONSUMER_KEY,
    'consumerSecret'    => CONSUMER_SECRET,
    'callbackUrl'       => CALLBACK_URL,
    'baseUrl'           => BASE_URL
]);

try {
    $requestToken = $client->getRequestToken();
    session_start();
    $_SESSION['REQUEST_TOKEN'] = serialize($requestToken);
    
} catch(\Exception $e) {
    echo "<p>Exception ".$e->getCode()."in ".$e->getFile()." on line ".$e->getLine().": ".$e->getMessage()."</p>";
    echo "<pre>";
    print($e->getTraceAsString());
    echo "</pre>";
}

$client->redirect($requestToken);
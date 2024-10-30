<?php

require '../vendor/autoload.php';

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;
use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\RESTClient;

include_once '../tests/bootstrap.php';

$basicAuthAccessor = new BasicAuthAccessor(BIBSONOMY_HOST_URL, API_USERNAME, API_KEY);
$restClient = new RESTClient($basicAuthAccessor);

$posts = $restClient->getPosts(Resourcetype::BIBTEX, Grouping::GROUP, 'kde',
    ['myown'], "", "", [], [], 'searchindex', 0, 20, 'xml')->model();
?>

<html>
<head>
    <title>Post example</title>
</head>
<body>
<h1>Posts of group KDE</h1>
<ol>
    <?php
        foreach ($posts as $post) {
            echo "<li>" . $post->getResource()->getTitle() . "</li>\n";
        }
    ?>
</ol>

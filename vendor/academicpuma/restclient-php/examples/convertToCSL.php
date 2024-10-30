<?php

require '../vendor/autoload.php';

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;
use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Renderer\CSLModelRenderer;
use AcademicPuma\RestClient\RESTClient;

include_once '../tests/bootstrap.php';

$basicAuthAccessor = new BasicAuthAccessor(BIBSONOMY_HOST_URL, API_USERNAME, API_KEY);
$restClient = new RESTClient($basicAuthAccessor);

$posts = $restClient->getPosts(Resourcetype::BIBTEX, Grouping::GROUP, 'kde',
    ['myown'], "", "", [], [], 'searchindex', 0, 10, 'xml')->model();

$cslRenderer = new CSLModelRenderer();
$cslPosts = [];
foreach ($posts as $post) {
    $cslPosts[] = $cslRenderer->render($post);
}

?>

<html>
<head>
    <title>Example: Convert posts to CSL</title>
</head>
<body>
<h1>Example: Convert posts to CSL</h1>
<ol>
    <?php
        foreach ($cslPosts as $csl) {
            echo "<li>" . json_encode($csl) . "</li>";
        }
    ?>
</ol>

<?php

require '../vendor/autoload.php';

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;
use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\Renderer\BibtexModelRenderer;
use AcademicPuma\RestClient\RESTClient;

include_once '../tests/bootstrap.php';

$basicAuthAccessor = new BasicAuthAccessor(BIBSONOMY_HOST_URL, API_USERNAME, API_KEY);
$restClient = new RESTClient($basicAuthAccessor);

$posts = $restClient->getPosts(Resourcetype::BIBTEX, Grouping::GROUP, 'kde',
    ['myown'], "", "", [], [], 'searchindex', 0, 10, 'xml')->model();

$bibtexRenderer = new BibtexModelRenderer();
$bibtexPosts = [];
foreach ($posts as $post) {
    $bibtexPosts[] = $bibtexRenderer->render($post);
}

?>

<html>
<head>
    <title>Example: Convert posts to BibTeX</title>
</head>
<body>
<h1>Example: Convert posts to BibTeX</h1>
<ol>
    <?php
        foreach ($bibtexPosts as $bibtex) {
            echo "<li>" . $bibtex . "</li>";
        }
    ?>
</ol>

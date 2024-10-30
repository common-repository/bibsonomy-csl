<?php

require '../vendor/autoload.php';

use AcademicPuma\RestClient\Config\Grouping;
use AcademicPuma\RestClient\Config\Resourcetype;
use AcademicPuma\RestClient\RESTClient;
use AcademicPuma\RestClient\Accessor\BasicAuthAccessor;

include_once '../tests/bootstrap.php';

$basicAuthAccessor = new BasicAuthAccessor(BIBSONOMY_HOST_URL, API_USERNAME, API_KEY);
$restClient = new RESTClient($basicAuthAccessor);

$bibliography = $restClient
    ->getPosts(
        Resourcetype::BIBTEX,
        Grouping::GROUP,
        "kde",
        ['myown'], "", "", "xml", 0, 200
    )
    ->bibliography("ieee", "en-US");
?>
<html>
<head>
    <title>Bibliography example</title>
</head>
<body>
<h1>Post of group KDE as bibliography in IEEE style</h1>
<?php echo $bibliography ?>
</body>
</html>
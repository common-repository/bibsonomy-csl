<?php

namespace AcademicPuma\RestClient\Queries\Get;

use AcademicPuma\RestClient\Config;
use AcademicPuma\RestClient\Renderer\TestHelper;
use PHPUnit\Framework\TestCase;

class GetDocumentQueryTest extends TestCase
{

    /**
     * @var GetDocumentQuery
     */
    protected $getDocumentQuery;

    /**
     *
     * @var string
     */
    private $intrahash = 'c49c9ef4dc754980aa36760133790f69';

    /**
     *
     * @var string
     */
    private $filename = 'Vergleich_Sortierverfahren.pdf';

    /**
     * @dataProvider executeProvider
     */
    public function testExecute($accessor, $username, $resourceHash, $fileName, $type)
    {

        $this->getDocumentQuery = new GetDocumentQuery($username, $resourceHash, $fileName, $type);

        // Excecute and check status code.
        $this->getDocumentQuery->execute($accessor->getClient());
        $statusCode = $this->getDocumentQuery->getStatusCode();
        $this->assertEquals('200', $statusCode);

        if ($type === Config\DocumentType::FILE) {

            // Check content.
            $body = $this->getDocumentQuery->getBody();
            $contentBinary = base64_encode($body);
            $this->assertContains('JVBERi0xLjUKJdDUxdgKMyAwIG9iaiA8PAovTGVuZ3RoIDIwODkgICAgICAK', $contentBinary);
        } else {

            /** @var \GuzzleHttp\Stream\Stream $stream */
            $actualStream = $this->getDocumentQuery->getStream();
            $actualBase64 = base64_encode($actualStream->getContents());

            $fileName .= '.png';

            $expectedBase64 = base64_encode(TestHelper::loadDocument($type . '_' . $fileName));

            $this->assertEquals($expectedBase64, $actualBase64);
        }

        // Is executed flag set?
        $this->assertTrue($this->getDocumentQuery->isExecuted());
    }

    public function executeProvider()
    {

        global $ACCESSOR_OAUTH, $ACCESSOR_BASICAUTH;

        return array(
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, $this->intrahash, $this->filename, Config\DocumentType::FILE),
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, $this->intrahash, $this->filename, Config\DocumentType::SMALL_PREVIEW),
            array($ACCESSOR_BASICAUTH, TEST_USER_ID, $this->intrahash, $this->filename, Config\DocumentType::LARGE_PREVIEW)
        );
    }
}

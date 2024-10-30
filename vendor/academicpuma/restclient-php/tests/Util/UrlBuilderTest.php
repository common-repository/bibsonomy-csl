<?php

namespace AcademicPuma\RestClient\Util;

use AcademicPuma\RestClient\Config;
use PHPUnit\Framework\TestCase;


class UrlBuilderTest extends TestCase
{

    /**
     * @var UrlBuilder
     */
    protected $urlBuilder;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->urlBuilder = new UrlBuilder();
    }

    /**
     * @covers AcademicPuma\RestClient\util\UrlBuilder::buildUrl
     * @todo   Implement testBuildUrl().
     */
    public function testBuildUrl()
    {

        // Check URL 1/3.
        $url1 = $this->urlBuilder->buildUrl(
            ['my', 'path'], // path
            [ // params
                Config\RESTConfig::START_PARAM => 0,
                Config\RESTConfig::END_PARAM => 5
            ]
        );

        $expectedUrl1 = 'api/my/path?start=0&end=5';
        $this->assertEquals($expectedUrl1, $url1);

        // Check URL 2/3.
        $url2 = $this->urlBuilder->buildUrl(
            ['yourPath', "APICALL!#$%&'( )*+,/:;=?@[]'"], // path
            [] // no params
        );

        $expectedUrl2 = 'api/yourPath/APICALL' . rawurlencode("!#$%&'( )*+,/:;=?@[]'");
        $this->assertEquals($expectedUrl2, $url2);

        // Check URL 3/3.
        $tags1 = array('tag1', 'tag2', 'tag3');

        $url3 = $this->urlBuilder->buildUrl(
            [Config\RESTConfig::CONCEPTS_URL, Config\RESTConfig::GROUPS_URL], //path
            [ //params
                Config\RESTConfig::TAGS_PARAM => $tags1,
                Config\RESTConfig::RESOURCE_PARAM => '#+21?0?237ß,<!-->#"'
            ]
        );

        $expectedUrl3 = 'api/' . Config\RESTConfig::CONCEPTS_URL . '/' . Config\RESTConfig::GROUPS_URL
            . '?tags=' . rawurlencode('tag1 tag2 tag3') . '&resource=' . rawurlencode('#+21?0?237ß,<!-->#"');
        $this->assertEquals($expectedUrl3, $url3);

        $tags2 = ['tag&1', 'tag%2', 'tag=3'];

        /**  Test URL Encoding */
        $url4 = $this->urlBuilder->buildUrl(
            ['Üiö#?/lkLh@h', '9k:asä%7&ua'], //path
            [ //params
                'ta%g+s' => $tags2
            ]
        );
        $expectedUrl4 = 'api/' . rawurlencode('Üiö#?/lkLh@h') . '/' . rawurlencode('9k:asä%7&ua') . '?' . rawurlencode('ta%g+s') . "=" . rawurlencode('tag&1 ') . rawurlencode('tag%2 ') . rawurlencode('tag=3');
        $this->assertEquals($expectedUrl4, $url4);
    }

}

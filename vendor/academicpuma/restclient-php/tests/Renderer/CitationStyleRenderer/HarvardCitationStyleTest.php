<?php

/*
 *  restclient-php is a full-featured REST client for PUMA and/or
 *  BibSonomy.
 *
 *  Copyright (C) 2015
 *
 *  Knowledge & Data Engineering Group,
 *  University of Kassel, Germany
 *  http://www.kde.cs.uni-kassel.de/
 *
 *  HothoData GmbH, Germany
 *  http://www.academic-puma.de
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace AcademicPuma\RestClient\CitationStyleRenderer;


use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Renderer\CitationRenderer;
use AcademicPuma\RestClient\Renderer\TestHelper;
use AcademicPuma\RestClient\Renderer\XMLModelUnserializer;
use Exception;
use PHPUnit\Framework\TestCase;

class HarvardCitationStyleTest extends TestCase
{

    /**
     * @var string
     */
    private static $stylesheetName = "harvard-cite-them-right";

    /**
     * @var string
     */
    private static $lang = "en-US";

    /**
     * @var string
     */
    private static $xmlPostsFile = "citationstyle-test.xml";

    /**
     * @var CitationRenderer
     */
    private $citationStyleRenderer;

    /**
     * @var Posts
     */
    private $posts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->citationStyleRenderer = new CitationRenderer(self::$stylesheetName, self::$lang);
        $xmlPosts = TestHelper::loadXML(self::$xmlPostsFile);
        $xmlModelUnserializer = new XMLModelUnserializer($xmlPosts);
        try {
            $this->posts = $xmlModelUnserializer->convertToModel();
        } catch (Exception $e) {
            $this->posts = new Posts();
        }
    }

    public function testRender()
    {
        $expected = TestHelper::loadCitation(self::$stylesheetName . ".txt");
        $actual = $this->citationStyleRenderer->renderPosts($this->posts);
        // remove content of possible style tags
        $actual = preg_replace('/<style[\s\S]+?<\/style>/', '', $actual);
        // clean up of the rendered posts, removing html tags, trim whitespace, decode special html characters
        $actual = htmlspecialchars_decode(trim(strip_tags($actual), " \t\n\r"));
        $arr_actual = array_filter(explode("\n", $actual));
        $arr_expected = array_filter(explode("\n", $expected));
        $this->assertEquals(count($arr_expected), count($arr_actual));
        foreach ($arr_actual as $entry) {
            $this->assertContains($entry, $arr_expected);
        }
    }

}
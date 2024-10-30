<?php
/*
 *
 * restclient-php is a full-featured REST client  written in PHP 
 * for PUMA and/or BibSonomy.
 * Copyright (C) 2015
 * 
 * This program is free software: you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation, either version 3 
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 */

namespace AcademicPuma\RestClient\Renderer;


use DOMException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class CSLModelRendererTest extends TestCase
{

    /**
     * @throws DOMException
     * @throws ReflectionException
     */
    public function testRender()
    {

        $renderer = new CSLModelRenderer();

        $xml_post_1 = TestHelper::loadXML('test-csl-post-1.xml');
        $post_1 = (new XMLModelUnserializer($xml_post_1))->convertToModel();
        $actual_post_1 = $renderer->render($post_1);
        $this->assertJson(json_encode($actual_post_1));
        $expected_post_1 = TestHelper::loadCSL('/test-csl-post-1.json');

        //TODO: write an encoding-save test
        //$this->assertStdClassObjectsEquals(json_decode($expected_post_1), $actual_post_1);

        $renderer = new CSLModelRenderer();

        $xml_post_2 = TestHelper::loadXML('test-csl-post-2.xml');
        $post_2 = (new XMLModelUnserializer($xml_post_2))->convertToModel();
        $actual_post_2 = $renderer->render($post_2);
        $this->assertJson(json_encode($actual_post_2));
        $expected_post_2 = TestHelper::loadCSL('/test-csl-post-2.json');

        //TODO: write an encoding-save test
        //$this->assertStdClassObjectsEquals(json_decode($expected_post_2), $actual_post_2);
    }


    private function assertStdClassObjectsEquals($expected, $actual)
    {

        echo "Check if expected attributes exist.\n";
        foreach ($expected as $attr => $val) {

            //$this->assertTrue());

            if (!isset($actual->{$attr})) {
                //echo "Attribute '$attr' does not exist!\n";
                $this->fail("Attribute '$attr' does not exist!");
            } else {
                $this->assertEquals($val, $actual->{$attr});
            }
        }

        echo "Check if actual attributes are expected.\n";
        foreach ($actual as $attr => $val) {
            if (!isset($expected->{$attr})) {
                echo "Attribute '$attr' not expected; value: '$val' (ignored).\n";
            } else {
                $this->assertEquals($val, $expected->{$attr});
            }
        }
    }
}

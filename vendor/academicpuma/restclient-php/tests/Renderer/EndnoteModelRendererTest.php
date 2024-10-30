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

namespace AcademicPuma\RestClient\Renderer;


use AcademicPuma\RestClient\Model\Bibtex;
use AcademicPuma\RestClient\Model\Post;
use PHPUnit\Framework\TestCase;

/**
 * Short description
 *
 * @since 16.07.15
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class EndnoteModelRendererTest extends TestCase
{
    protected function setUp(): void
    {
        $this->resource = new Bibtex();
        $this->resource
            ->setEntrytype('presentation')
            ->setBibtexKey('boettger2014puma')
            ->setTitle('PUMA als Schnittstelle zwischen Discovery Service, Institutional Repository und eLearning')
            ->setBibtexAbstract('bla')
            ->setAddress('Reutlingen')
            ->setAuthor('Böttger, Sebastian')
            ->setMonth('07')
            ->setPublisher('Berufsverband Information Bibliothek')
            ->setYear('2014')
            ->setUrl('http://www.opus-bayern.de/bib-info/volltexte//2014/1674');
        $this->post = new Post();
        $this->post->setResource($this->resource);
    }

    public function testSerializeResource()
    {
        $endnoteRenderer = new EndnoteModelRenderer();
        $actual = $endnoteRenderer->render($this->resource);
        $expected = TestHelper::loadEndnote('bibtex.endnote');

        print_r($actual);
        echo "\n";
        $this->assertStringStartsWith('%', $actual);
        $this->assertStringEndsWith("\n", $actual);

        $this->assertEquals($expected, $actual);
    }

    public function testSerializePosts()
    {
        $endnoteRenderer = new EndnoteModelRenderer();
        $actual = $endnoteRenderer->render($this->post);
        print_r($actual);
        echo "\n";
        $this->assertStringStartsWith('%', $actual);
        $this->assertStringEndsWith("\n", $actual);

    }
}

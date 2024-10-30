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
use AcademicPuma\RestClient\Model\Bookmark;
use AcademicPuma\RestClient\Model\Exceptions\InvalidModelObjectException;
use AcademicPuma\RestClient\Model\Post;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Model\Resource;
use AcademicPuma\RestClient\Util;
use ReflectionClass;
use ReflectionException;

/**
 * Creates bibtex string from a model.
 *
 * @since 09.07.15
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class BibtexModelRenderer extends ModelRenderer
{

    const EQUALS_SIGN = '=';
    const TAB = ' ';
    const NEWLINE = PHP_EOL;
    const COMMA_AND_NEWLINE = ",\n";
    const LAST_COMMA = "/^(.+?),\n$/";

    protected $includeMiscField;

    /**
     * @param bool $includeMiscField
     */
    public function __construct(bool $includeMiscField = false)
    {
        $this->includeMiscField = $includeMiscField;
    }

    /**
     * Serializes a <pre>$resource</pre> into its BibTeX representation
     *
     * @param Resource $resource
     *
     * @return string
     * @throws InvalidModelObjectException
     * @throws ReflectionException
     */
    function serializeResource(Resource $resource): string
    {
        if ($resource instanceof Bookmark) {
            throw new InvalidModelObjectException("There is no BibTeX representation for resource type
            Bookmark");
        }
        return $this->serializeBibTex($resource);
    }

    /**
     * Serializes a <pre>$post</pre> recursively into its XML representation
     *
     * @param Post $post
     *
     * @return string
     * @throws InvalidModelObjectException
     * @throws ReflectionException
     */
    function serializePost(Post $post): string
    {
        return $this->serializeResource($post->getResource());
    }

    /**
     * Serializes <pre>$posts </pre> recursively into its XML representation
     *
     * @param Posts $posts
     *
     * @return string BibTeX formatted posts
     * @throws InvalidModelObjectException
     * @throws ReflectionException
     */
    function serializePosts(Posts $posts): string
    {
        $bibtexPosts = [];
        foreach ($posts as $post) {
            $bibtexPosts[] = $this->serializePost($post);
            //$this->bibtexPost = '';
        }
        return implode(PHP_EOL, $bibtexPosts);
    }

    /**
     * @param Bibtex $bibtex
     *
     * @return string
     * @throws ReflectionException
     */
    protected function serializeBibTex(Bibtex $bibtex): string
    {

        $bibtexBody = '';

        $bibtexFields = ["abstract", "address", "annote", "author", "booktitle", "chapter", "crossref", "edition",
            "editor", "howpublished", "institution", "journal", "keywords", "misc", "month", "note", "number",
            "organization", "pages", "publisher", "school", "series", "title", "type", "volume", "year"];

        $reflectedBibtex = new ReflectionClass($bibtex);

        for ($i = 0; $i < count($bibtexFields); ++$i) {

            $field = $bibtexFields[$i];

            if ($field === 'abstract') {

                if ($bibtex->getBibtexAbstract() != null && $bibtex->getBibtexAbstract() != '') {
                    $bibtexBody .= $this->renderBibTeXAttribute($field, $bibtex->getBibtexAbstract());
                }
                continue;
            }

            if ($field === 'misc') {
                $misc = $bibtex->getMisc();
                if ($this->includeMiscField && !empty($misc)) {
                    $bibtexBody .= $this->renderMiscFields($misc);
                }
                continue;
            }

            if ($field === 'keywords' && !is_null($bibtex->getKeywords())) {
                $keywordString = implode(' ', $bibtex->getKeywords());
                $bibtexBody .= $this->renderBibTeXAttribute($field, $keywordString);
                continue;
            }
            $method = $reflectedBibtex->getMethod('get' . ucfirst($field));
            $value = $method->invoke($bibtex);

            if (!empty($value)) {
                $bibtexBody .= $this->renderBibTeXAttribute($field, $value, $bibtexBody);
            }
        }

        $bibtexBody = $this->removeLastComma($bibtexBody);

        $bibtexBody .= self::NEWLINE;
        $bibtexItem = '@' .
            $bibtex->getEntrytype() .
            $this->addBibTexBrackets(
                $bibtex->getBibtexKey() .
                self::COMMA_AND_NEWLINE .
                $bibtexBody
            ) . self::NEWLINE;

        return $bibtexItem;
    }

    /**
     * @param $field
     * @param $value
     *
     * @return string
     */
    protected function renderBibTeXAttribute($field, $value): string
    {

        $value = trim($value); //trim
        $part = self::TAB . ' ' . $field . ' ' . self::EQUALS_SIGN . ' ';

        if (preg_match('/^[1-9][0-9]*$/', $value)) {
            $part .= $value;
        } else {
            $part .= $this->addBibTexBrackets($value);
        }

        $part .= self::COMMA_AND_NEWLINE;

        return $part;
    }

    protected function addBibTexBrackets($string): string
    {
        return '{' . $string . '}';
    }

    /**
     * @param string $misc
     *
     * @return string
     */
    private function renderMiscFields(string $misc): string
    {
        $miscAttrArray = Util\BibtexUtils::parseMiscFieldString($misc);
        $miscAttributes = '';
        foreach ($miscAttrArray as $attrName => $attr) {
            $miscAttributes .= $this->renderBibTeXAttribute($attrName, $attr);
        }
        return $miscAttributes;
    }

    private function removeLastComma($bibtexBody)
    {

        $cutPos = strpos($bibtexBody, ",\n", strlen($bibtexBody) - 2);
        return substr($bibtexBody, 0, $cutPos);
    }

}

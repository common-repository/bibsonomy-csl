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
use AcademicPuma\RestClient\Util\BibtexUtils;
use AcademicPuma\RestClient\Util\EndnoteUtils;
use AcademicPuma\RestClient\Util\PersonUtils;

/**
 * Short description
 *
 * @since 09.07.15
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class EndnoteModelRenderer extends ModelRenderer
{
    const NEWLINE = PHP_EOL;

    /**
     * Serializes a <pre>$resource</pre> into its BibTeX representation
     *
     * @param Resource $resource
     *
     * @return string
     * @throws InvalidModelObjectException
     */
    function serializeResource(Resource $resource): string
    {
        if ($resource instanceof Bookmark) {
            throw new InvalidModelObjectException("There is no BibTeX representation for resource type Bookmark");
        }
        return $this->serializeEndnote($resource);
    }

    /**
     * Serializes a <pre>$post</pre> recursively into its XML representation
     *
     * @param Post $post
     *
     * @return string
     * @throws InvalidModelObjectException
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
     */
    function serializePosts(Posts $posts): string
    {
        $bibtexPosts = [];
        foreach ($posts as $post) {
            $bibtexPosts[] = $this->serializePost($post);
            //$this->bibtexPost = '';
        }
        return implode("\n", $bibtexPosts);
    }

    /**
     * @param Bibtex $bibtex
     *
     * @return string
     */
    protected function serializeEndnote(Bibtex $bibtex): string
    {
        $endnoteItem = "%0 " . EndnoteUtils::getEntrytype($bibtex->getEntrytype()) . self::NEWLINE;

        if (self::present($bibtex->getBibtexKey())) {
            $endnoteItem .= "%1 " . BibtexUtils::cleanBibTex($bibtex->getBibtexKey()) . self::NEWLINE;
        }

        if (self::present($bibtex->getAuthor())) {

            $persons = PersonUtils::createPersonsListFromString($bibtex->getAuthor());
            foreach ($persons as $person) {
                /** @var PersonUtils $person */
                $endnoteItem .= "%A " . $person->getLastName() . ", " . $person->getFirstName() . self::NEWLINE;
            }
        }

        if (self::present($bibtex->getBooktitle())) {
            $endnoteItem .= "%B " . BibtexUtils::cleanBibtex($bibtex->getBooktitle()) . self::NEWLINE;
        } else if (self::present($bibtex->getSeries())) {// TODO: check whether this is correct
            $endnoteItem .= "%B " . BibtexUtils::cleanBibtex($bibtex->getSeries()) . self::NEWLINE;
        }

        if (self::present($bibtex->getAddress())) {
            $endnoteItem .= "%C " . BibtexUtils::cleanBibtex($bibtex->getAddress()) . self::NEWLINE;
        }

        if (self::present($bibtex->getYear())) {
            $endnoteItem .= "%D " . BibtexUtils::cleanBibtex($bibtex->getYear()) . self::NEWLINE;
        }

        if (self::present($bibtex->getEditor())) {

            $persons = PersonUtils::createPersonsListFromString($bibtex->getEditor());
            foreach ($persons as $person) {
                $endnoteItem .= "%E " . $person->getLastName() . ", " . $person->getFirstName() . self::NEWLINE;
            }
        }

        if (self::present($bibtex->getPublisher())) {
            $endnoteItem .= "%I " . BibtexUtils::cleanBibtex($bibtex->getPublisher()) . self::NEWLINE;
        }

        if (self::present($bibtex->getJournal())) {
            $endnoteItem .= "%J " . BibtexUtils::cleanBibtex($bibtex->getJournal()) . self::NEWLINE;
        }

        if (self::present($bibtex->getNumber())) {
            $endnoteItem .= "%N " . BibtexUtils::cleanBibtex($bibtex->getNumber()) . self::NEWLINE;
        }

        if (self::present($bibtex->getPages())) {
            $endnoteItem .= "%P " . BibtexUtils::cleanBibtex($bibtex->getPages()) . self::NEWLINE;
        }

        if (self::present($bibtex->getMiscField("doi"))) {
            $endnoteItem .= "%R " . BibtexUtils::cleanBibtex($bibtex->getMiscField("doi")) . self::NEWLINE;
        }

        if (self::present($bibtex->getTitle())) {
            $endnoteItem .= "%T " . BibtexUtils::cleanBibtex($bibtex->getTitle()) . self::NEWLINE;
        }

        if (self::present($bibtex->getUrl())) {
            $endnoteItem .= "%U " . BibtexUtils::cleanBibtex($bibtex->getUrl()) . self::NEWLINE;
        }

        if (self::present($bibtex->getVolume())) {
            $endnoteItem .= "%V " . BibtexUtils::cleanBibtex($bibtex->getVolume()) . self::NEWLINE;
        }

        if (self::present($bibtex->getBibtexAbstract())) {
            $endnoteItem .= "%X " . BibtexUtils::cleanBibtex($bibtex->getBibtexAbstract()) . self::NEWLINE;
        }

        if (self::present($bibtex->getAnnote())) {
            $endnoteItem .= "%Z " . BibtexUtils::cleanBibtex($bibtex->getAnnote()) . self::NEWLINE;
        }

        if (self::present($bibtex->getEdition())) {
            $endnoteItem .= "%7 " . BibtexUtils::cleanBibtex($bibtex->getEdition()) . self::NEWLINE;
        }

        if (self::present($bibtex->getChapter())) {
            $endnoteItem .= "%& " . BibtexUtils::cleanBibtex($bibtex->getChapter()) . self::NEWLINE;
        }

        if (self::present($bibtex->getMiscField("isbn"))) {
            $endnoteItem .= "%@ " . BibtexUtils::cleanBibtex($bibtex->getMiscField("isbn")) . self::NEWLINE;
        }

        return $endnoteItem;
    }


    /**
     * @param string $endnoteString
     *
     * @return string
     */
    private function removeLastComma(string $endnoteString): string
    {
        $cutPos = strpos($endnoteString, ",\n", strlen($endnoteString) - 2);
        return substr($endnoteString, 0, $cutPos);
    }

    /**
     * @param mixed $var
     *
     * @return bool
     */
    private static function present($var): bool
    {
        return !empty($var);
    }
}

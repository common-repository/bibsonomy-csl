<?php

/*
    This file is part of BibSonomy/PUMA CSL for WordPress.

    BibSonomy/PUMA CSL for WordPress is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    BibSonomy/PUMA CSL for WordPress is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with BibSonomy/PUMA CSL for WordPress.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * CitationUtils
 */
class CitationUtils
{

    /**
     * Wrap the list of authors in citation in a <span>-element.
     * Additionally, includes links for each individual author.
     *
     * @param $cslItem
     * @param $renderedText
     * @return string
     */
    public static function authorWithLink($cslItem, $renderedText): string {
        // TODO
        return self::authorWithoutLink($cslItem, $renderedText);
    }

    /**
     * Wrap the list of authors in citation in a <span>-element.
     *
     * @param $cslItem
     * @param $renderedText
     * @return string
     */
    public static function authorWithoutLink($cslItem, $renderedText): string {
        return '<span class="csl-author">' . $renderedText . '</span>';
    }

    /**
     * Wrap the title of the citation in a <span>-element.
     * Additionally, include a separate link to the publication.
     *
     * @param $cslItem
     * @param $renderedText
     * @return string
     */
    public static function titleWithLink($cslItem, $renderedText): string {
        if (!empty($cslItem->URL)) {
            return '<span class="csl-title"><a href="' . $cslItem->URL . '">' . htmlspecialchars_decode($renderedText) . '</a></span>';
        }

        return self::titleWithoutLink($cslItem, $renderedText);
    }

    /**
     * Wrap the title of the citation in a <span>-element.
     *
     * @param $cslItem
     * @param $renderedText
     * @return string
     */
    public static function titleWithoutLink($cslItem, $renderedText): string {
        return '<span class="csl-title">' . htmlspecialchars_decode($renderedText) . '</span>';
    }

    /**
     * Wrap the citation number of the citation in a <span>-element.
     *
     * @param $cslItem
     * @param $renderedText
     * @return string
     */
    public static function citationNumber($cslItem, $renderedText): string {
        return '<span class="csl-number">' . $renderedText . '</span>';
    }

    /**
     * Hide the element of the citation.
     *
     * @param $cslItem
     * @param $renderedText
     * @return string
     */
    public static function hidden($cslItem, $renderedText): string {
        return '<span style="display: none;">' . $renderedText . '</span>';
    }

}
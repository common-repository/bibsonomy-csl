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

use AcademicPuma\RestClient\Authentication\BasicAuthAccessor;
use AcademicPuma\RestClient\Config\Sorting;
use AcademicPuma\RestClient\Model\Exceptions\UnsupportedOperationException;
use AcademicPuma\RestClient\Model\Posts;
use AcademicPuma\RestClient\Renderer\BibtexModelRenderer;
use AcademicPuma\RestClient\Renderer\CSLModelRenderer;
use AcademicPuma\RestClient\Renderer\EndnoteModelRenderer;
use AcademicPuma\RestClient\RESTClient;
use Seboettg\CiteProc\CiteProc;
use Seboettg\CiteProc\Exception\CiteProcException;

require_once 'BibsonomyHelper.php';
require_once 'MimeTypeMapper.php';
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Description of BibsonomyAPI
 *
 * @author Sebastian Böttger
 */
class BibsonomyAPI
{

    public $additionalMarkup = [];

    /**
     * BibsonomyAPI constructor.
     */
    public function __construct()
    {
        $this->additionalMarkup = [
            "bibliography" => [
                "citation-number" => [
                    'function' => function($cslItem, $renderedText) {
                        return '<span class="csl-number">' . $cslItem->citationNumber . '</span>';
                    },
                    'affixes' => true
                ],
                "author" => [
                    'function' => function($cslItem, $renderedText) {
                        return '<span class="csl-author">' . $renderedText . '</span>';
                    },
                    'affixes' => true
                ],
                "title" => [
                    'function' => function($cslItem, $renderedText) {
                        return '<span class="csl-title">' . htmlspecialchars_decode($cslItem->title) . '</span>';
                    },
                    'affixes' => true
                ],
                "macro" => [
                    'affixes' => true
                ]
            ]
        ];
    }


    /**
     *
     * @param array $args
     *
     * @return string
     * @throws UnsupportedOperationException
     */
    public function renderPublications($args)
    {
        global $wpdb, $post, $BIBSONOMY_OPTIONS, $locale;
        if ($args['override-username'] != '' && $args['override-api-key'] != '') {
            $accessor = new BasicAuthAccessor($args['host'], $args['override-username'], $args['override-api-key']);
        } else {
            $accessor = new BasicAuthAccessor($args['host'], $args['user'], $args['apikey']);
        }

        $start = 0;
        $end = ($args['end'] != '') ? $args['end'] : 100;
        $restclient = new RESTClient($accessor, ['verify' => true]);
        try {
            $restclient->getPosts('bibtex', $args['type'], $args['val'],
                explode(" ", $args['tags']), '', $args['search'], [], [], 'local', $start, $end);
            $publications = new Posts($restclient->model()->toArray());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return "<div class='bibsonomycsl_error'><b>BibSonomy CSL Error: </b> Please check your PUMA Host URL.</div>";
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return "<div class='bibsonomycsl_error'><b>BibSonomy CSL Error: </b> Please check your API user and key.</div>";
        }

        /*
         * Filter out duplicates from posts
         */
        if ($args['filter-duplicates']) {
            $this->filterDuplicates($publications, $args['filter-duplicates']);
        }

        $stylesheet = "";

        if ($args['stylesheet'] !== "url") {

            $table_name = $wpdb->prefix . "bibsonomy_csl_styles";

            try {
                if (count($publications) > 0) {
                    if ($args['style'] === '') {
                        $query = "SELECT xml_source FROM $table_name WHERE id='" . $args['stylesheet'] . "';";

                        $results = $wpdb->get_results($query);
                        $stylesheet = $results[0]->xml_source;
                    } else {
                        $stylesheet = $this->fetchStylesheet($args);
                    }
                } else {
                    return "";
                }
            } catch (Exception $e) {

                return '<p style="border: 1px solid #f00; padding: 0.5em 1em;">Error: ' . $e->getMessage() . '</p>' . "\n<!--" . $e->getTraceAsString() . "-->\n";
            }
        } else {
            $stylesheet = $this->fetchStylesheet($args);
        }

        $ret = '';

        /*
         * Add inline search input
         */
        if ($args["inline-search"]) {
            $ret .= '<div class="tx-extbibsonomy-csl-publication-filter">
                        <input type="text" name="filterInput"/>
                        <input type="button" name="filterButton" value="Filter"/>
                        <input type="button" name="filterClear" value="Clear"/>
                    </div>';
        }

        /*
         * Sort posts and prepare grouping
         */
        if ($args["groupyear"] != '') {
            // Sort by year first for grouping
            $publications->sort('year', Sorting::ORDER_DESC);

            // Group posts by year
            $groupedPublications = $this->transformToYearGroupedArray($publications);

            // Sort within sublists of groups
            $groupKeys = array_keys($groupedPublications); //get groups
            foreach ($groupKeys as $groupKey) {
                if ($publications[$groupKey] instanceof Posts) {
                    $sublist = new Posts($groupedPublications[$groupKey]->toArray());
                } else {
                    $sublist = new Posts($groupedPublications[$groupKey]);
                }

                $sublist->sort($args['sorting-type'], $args['sorting-order'] === 'asc' ? Sorting::ORDER_ASC : Sorting::ORDER_DESC);
                $groupedPublications[$groupKey] = $sublist;
            }

            /*
             * Add grouping anchors above publication list
             */
            if ($args["groupyear"] == "grouping-anchors") {
                $ret .= '<div class="bibsonomycsl_jump_list">';
                $ret .= $this->renderGroupingAnchors($groupKeys);
                $ret .= '</div>';
            }
        } else {
            $publications->sort($args['sorting-type'], $args['sorting-order'] === 'asc' ? Sorting::ORDER_ASC : Sorting::ORDER_DESC);
            $groupedPublications[''] = $publications;
        }

        $ret .= '<ul class="' . BibsonomyCsl::PREFIX . 'publications">';
        $bibtexRenderer = new BibtexModelRenderer();
        $endnoteRenderer = new EndnoteModelRenderer();

        foreach ($groupedPublications as $groupKey => $publications) {
            if ($args["groupyear"] == "grouping" || $args["groupyear"] == "grouping-anchors") {
                $ret .= "\n</ul>";
                $ret .= "\n<a class=\"" . BibsonomyCsl::PREFIX . "publications-headline-anchor \" name=\"jmp_" . BibsonomyHelper::replaceSpecialCharacters($groupKey) . "\"></a><h3 class=\"" . BibsonomyCsl::PREFIX . "publications-headline\" style=\"font-size: 1.1em; font-weight: bold;\">$groupKey</h3>";
                $ret .= "\n<ul class=\"" . BibsonomyCsl::PREFIX . "publications\">";
            }
            foreach ($publications as $publication) {
                $ret .= '<li class="' . BibsonomyCsl::PREFIX . 'pubitem">';

                if ($args['preview']) {
                    if (!empty($publication->getDocuments())) {

                        $doc = $restclient->getDocumentFile($publication->getUser()->getName(), $publication->getResource()->getIntrahash(), $publication->getDocuments()[0]->getFilename(), "SMALL")->file();
                        $document_thumbnail_url = add_query_arg(array(
                            'action' => 'preview',
                            'userName' => $publication->getUser()->getName(),
                            'intraHash' => $publication->getResource()->getIntrahash(),
                            'fileName' => urlencode($publication->getDocuments()[0]->getFilename()),
                            'size' => 'SMALL',
                            'doc' => $doc
                        ), get_permalink($post->ID));
                        $ret .= '<div class="' . BibsonomyCsl::PREFIX . 'preview_border">';
                        $document_preview_url = add_query_arg(array(
                            'action' => 'preview',
                            'userName' => $publication->getUser()->getName(),
                            'intraHash' => $publication->getResource()->getIntrahash(),
                            'fileName' => urlencode($publication->getDocuments()[0]->getFilename()),
                            'size' => 'LARGE'
                        ), get_permalink($post->ID));

                        $ret .= '<img onmouseover="javascript:showtrail(\'' . $document_preview_url . '\')" onmouseout="javascript:hidetrail()" class="' . BibsonomyCsl::PREFIX . 'preview" src="' . $document_thumbnail_url . '" /></div>';
                    } else {
                        //default value
                        $type = empty($publication->getResource()->getEntrytype()) ? 'misc' : $publication->getResource()->getEntrytype();
                        //render entry type preview
                        $ret .= '<div class="' . BibsonomyCsl::PREFIX . 'preview_border ' . BibsonomyCsl::PREFIX . 'preview_thumb">
                                        <span>
                                            <img class="bibsonomycsl_preview" style="z-index: 1;" src="' . plugins_url('/bibsonomy-csl/img/entrytypes/' . $type . '.jpg') . '" />
                                        </span>
                                 </div>';
                    }
                }

                $ret .= '<div class="' . BibsonomyCsl::PREFIX . 'entry">';

                try {
                    $cslRenderer = new CSLModelRenderer();
                    $citeProc = new CiteProc($stylesheet, 'en-US', $this->additionalMarkup);
                    $csl = $cslRenderer->render($publication);
                    $ret .= $citeProc->render(json_decode(json_encode(array($csl))));
                } catch (Exception | CiteProcException $e) {
                    // noop
                }

                if ($args['abstract'] && !empty($publication->getResource()->getBibtexAbstract())) {
                    $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'export ' . BibsonomyCsl::PREFIX . 'abstract"><a rel="abs-' . $publication->getResource()->getIntrahash() . '"  href="#">Abstract</a></span>';
                }

                if ($args['links']) {
                    if ($publication->getResource()->getUrl()) {
                        $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'url"><a href="' . $publication->getResource()->getUrl() . '" target="_blank">URL</a></span>';
                    }
                    $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'export ' . BibsonomyCsl::PREFIX . 'bibtex"><a rel="bib-' . $publication->getResource()->getIntrahash() . '" href="' . "#" . '">BibTeX</a></span>';
                    $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'export ' . BibsonomyCsl::PREFIX . 'endnote"><a rel="end-' . $publication->getResource()->getIntrahash() . '" href="' . "#" . '">EndNote</a></span>';
                }

                if ($args['doi-link'] && $publication->getResource()->getMiscField('doi')) {
                    $doiUrl = $this->getDoiUrl($publication->getResource()->getMiscField('doi'));
                    $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'url"><a href="' . $doiUrl . '" target="_blank">DOI</a></span>';
                }

                if ($args['host-link']) {
                    $hostUrl = $this->cleanApiUrl($publication->getResource()->getHref());
                    if (strpos($hostUrl, 'bibsonomy.org') !== false) {
                        $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'url"><a href="' . $hostUrl . '" target="_blank">BibSonomy</a></span>';
                    } else {
                        $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'url"><a href="' . $hostUrl . '" target="_blank">PUMA-Post</a></span>';
                    }
                }

                if ($args['download']) {
                    if (!empty($publication->getDocuments())) {
                        $document_download_url = add_query_arg(array(
                            'action' => 'download',
                            'userName' => $publication->getUser()->getName(),
                            'intraHash' => $publication->getResource()->getIntrahash(),
                            'fileName' => urlencode($publication->getDocuments()[0]->getFilename())
                        ), get_permalink($post->ID));

                        $ret .= '<span class="' . BibsonomyCsl::PREFIX . 'download"><a href="' . $document_download_url . '" target="_blank">Download</a></span>';
                    }
                }


                $ret .= '<div style="clear: left"> </div>';

                if (!empty($publication->getResource()->getBibtexAbstract())) {
                    $ret .= '<div class="' . BibsonomyCsl::PREFIX . 'collapse ' . BibsonomyCsl::PREFIX . 'pub_abstract" style="display:none;" id="abs-' . $publication->getResource()->getIntraHash() . '">' . $publication->getResource()->getBibtexAbstract() . '</div>';
                }

                if ($args['links']) {
                    $publication->getResource()->setKeywords($publication->getTag()->toArray());
                    $bibtex = str_replace("\n", "<br/>", $bibtexRenderer->render($publication->getResource()));
                    $endnote = str_replace("\n", "<br/>", $endnoteRenderer->render($publication));

                    $ret .= '<div class="' . BibsonomyCsl::PREFIX . 'collapse ' . BibsonomyCsl::PREFIX . 'pub_bibtex" style="display:none;" id="bib-' . $publication->getResource()->getIntraHash() . '">' .
                        '<p>' . $bibtex . '</p>' .
                        '</div>';
                    $ret .= '<div class="' . BibsonomyCsl::PREFIX . 'collapse ' . BibsonomyCsl::PREFIX . 'pub_endnote" style="display:none;" id="end-' . $publication->getResource()->getIntraHash() . '">' .
                        '<p>' . $endnote . '</p>' .
                        '</div>';
                }

                $ret .= '</div>';
                $ret .= '</li>';
            }
        }

        $ret .= '</ul>';

        return $ret;
    }

    /**
     * transforms publications list to multidimensional Posts Array List:
     * 1st dimension represents the year of the data content
     *
     * @param Posts $posts pre-sorted (by year) publication list
     */
    private function transformToYearGroupedArray(Posts $posts)
    {
        $groupedPosts = [];
        if (!empty($posts->toArray())) {
            $year = $posts[0]->getResource()->getYear();
            foreach ($posts as $post) {
                if ($post->getResource()->getYear() !== $year) {
                    $year = $post->getResource()->getYear();
                }
                $groupedPosts[$year][] = $post;
            }
            return $groupedPosts;
        }
    }

    private function renderGroupingAnchors($yearLabels)
    {
        $array = array();
        foreach ($yearLabels as $year) {
            $array[$year] = '[<a class="bibsonomycsl_publications-headline-jumplabel" href="#jmp_' . BibsonomyHelper::replaceSpecialCharacters($year) . '" title="Goto ' . $year . '">' . $year . '</a>]';
        }

        return implode(" ", array_values($array));
    }

    private function fetchStylesheet($args)
    {

        if (!isset($args['style']) || $args['style'] == "") {
            throw new Exception("No style given, please select a CSL in the configuration!");
        }

        return file_get_contents($args['style']);
    }

    private function filterDuplicates(Posts &$posts, $filterDuplicateSetting)
    {
        if ($filterDuplicateSetting != 'none') {
            $hashes = array();
            foreach ($posts as $key => $post) {
                if ($post != null) {
                    // Either use inter- or intrahash depending on the plugin settings.
                    $hash = $filterDuplicateSetting == 'intrahash' ? $post->getResource()->getIntraHash() : $post->getResource()->getInterHash();
                    if (in_array($hash, $hashes)) {
                        unset($posts[$key]);
                    } else {
                        $hashes[] = $hash;
                    }
                }
            }
        }
    }

    private function cleanApiUrl($url)
    {
        if (strpos($url, 'bibsonomy.org') !== false) {
            /*
             * If clause for BibSonomy, because of API-links in each individual link for posts
             * and a separate label for the post link
             */
            if (strpos($url, 'bibsonomy.org/api/') !== false) {
                // API-Link, example: https://www.bibsonomy.org/api/users/username/posts/intrahash
                $hrefArr = explode('/', $url);
                $bibHref = 'https://www.bibsonomy.org/bibtex/' . $hrefArr[7] . '/' . $hrefArr[5];
            } else {
                $bibHref = $url;
            }
            return $bibHref;
        } else {
            return $url;
        }
    }

    private function getDoiUrl($doi)
    {
        if (strpos($doi, 'doi.org') !== false) {
            return $doi;
        } else {
            return 'https://dx.doi.org/' . $doi;
        }
    }
}

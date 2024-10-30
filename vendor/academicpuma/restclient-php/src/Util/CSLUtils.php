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

namespace AcademicPuma\RestClient\Util;


use AcademicPuma\RestClient\Config\CSLType;
use AcademicPuma\RestClient\Config\Entrytype;
use AcademicPuma\RestClient\Model\Exceptions\FileNotFoundException;

class CSLUtils
{

    const CSL_TYPE_DEFAULT = CSLType::MISC;

    const TYPE_MAP = [
        //Articles
        Entrytype::ARTICLE => CSLType::ARTICLE_JOURNAL,

        //Books
        Entrytype::BOOK => CSLType::BOOK,
        Entrytype::PROCEEDINGS => CSLType::BOOK,
        Entrytype::PERIODICAL => CSLType::BOOK,
        Entrytype::MANUAL => CSLType::BOOK,

        //Booklet
        Entrytype::BOOKLET => CSLType::PAMPHLET,

        //Chapter
        Entrytype::INBOOK => CSLType::CHAPTER,
        Entrytype::INCOLLECTION => CSLType::CHAPTER,

        //Conference
        Entrytype::INPROCEEDINGS => CSLType::PAPER_CONFERENCE,
        Entrytype::CONFERENCE => CSLType::PAPER_CONFERENCE,

        //Thesis
        Entrytype::PHDTHESIS => CSLType::THESIS,
        Entrytype::MASTERTHESIS => CSLType::THESIS,

        Entrytype::TECHREPORT => CSLType::REPORT,
        Entrytype::PATENT => CSLType::PATENT,

        Entrytype::ELECTRONIC => CSLType::WEBPAGE,

        Entrytype::MISC => CSLType::ARTICLE,

        Entrytype::STANDARD => CSLType::LEGISLATION,

        Entrytype::UNPUBLISHED => CSLType::MANUSCRIPT,
        Entrytype::PREPRINT => CSLType::MANUSCRIPT,

        Entrytype::PRESENTATION => CSLType::SPEECH
    ];

    const LANG_BASES = [
        "af" => "af-ZA",
        "ar" => "ar-AR",
        "bg" => "bg-BG",
        "ca" => "ca-AD",
        "cs" => "cs-CZ",
        "da" => "da-DK",
        "de" => "de-DE",
        "el" => "el-GR",
        "en" => "en-US",
        "es" => "es-ES",
        "et" => "et-EE",
        "fa" => "fa-IR",
        "fi" => "fi-FI",
        "fr" => "fr-FR",
        "he" => "he-IL",
        "hu" => "hu-HU",
        "is" => "is-IS",
        "it" => "it-IT",
        "ja" => "ja-JP",
        "km" => "km-KH",
        "ko" => "ko-KR",
        "mn" => "mn-MN",
        "nb" => "nb-NO",
        "nl" => "nl-NL",
        "nn" => "nn-NO",
        "pl" => "pl-PL",
        "pt" => "pt-PT",
        "ro" => "ro-RO",
        "ru" => "ru-RU",
        "sk" => "sk-SK",
        "sl" => "sl-SI",
        "sr" => "sr-RS",
        "sv" => "sv-SE",
        "th" => "th-TH",
        "tr" => "tr-TR",
        "uk" => "uk-UA",
        "vi" => "vi-VN",
        "zh" => "zh-CN",
    ];

    public static function getEntrytype($type): string
    {
        if (array_key_exists($type, self::TYPE_MAP)) {
            return self::TYPE_MAP[$type];
        }

        return self::CSL_TYPE_DEFAULT;
    }

    /**
     * @param null $family
     * @param null $given
     * @param null $droppingParticle
     * @param null $nonDroppingParticle
     * @param null $suffix
     * @param bool $commaPrefix
     * @param bool $commaSuffix
     * @param bool $staticOrdering
     * @param bool $literal
     * @param bool $parseNames
     *
     * @return \stdClass
     */
    public static function cslName($family = null, $given = null, $droppingParticle = null,
                                   $nonDroppingParticle = null, $suffix = null, $commaPrefix = false,
                                   $commaSuffix = false, $staticOrdering = false, $literal = false,
                                   $parseNames = false): \stdClass
    {
        $cslName = new \stdClass();

        if ($family != null) {
            $cslName->family = $family;
        }
        if ($given != null) {
            $cslName->given = $given;
        }
        if ($droppingParticle != null) {
            $cslName->{"dropping-particle"} = $droppingParticle;
        }
        if ($nonDroppingParticle != null) {
            $cslName->{"non-dropping-particle"} = $nonDroppingParticle;
        }
        if ($suffix != null) {
            $cslName->suffix = $suffix;
        }
        if ($commaPrefix != null) {
            $cslName->{"comma-prefix"} = $commaPrefix;
        }
        if ($commaSuffix != null) {
            $cslName->{"comma-suffix"} = $commaSuffix;
        }
        if ($staticOrdering != null) {
            $cslName->{"static-ordering"} = $staticOrdering;
        }
        if ($literal != null) {
            $cslName->{"literal"} = $literal;
        }
        if ($parseNames != null) {
            $cslName->{"parse-names"} = $parseNames;
        }

        return $cslName;
    }

    /**
     * @param $authorString
     *
     * @return array
     */
    public static function getCSLNames($authorString): array
    {
        $personArray = explode(PersonUtils::PERSON_NAME_DELIMITER, $authorString);
        $author = PersonUtils::createPersonsListFromArray($personArray);

        if (empty($author)) {
            return [];
        }

        $cslNames = [];

        for ($i = 0; $i < count($author); ++$i) {

            /** @var PersonUtils $personName */
            $personName = $author[$i];
            $cslNameObj = self::cslName(BibtexUtils::cleanBibtex($personName->getLastName()), $personName->getFirstName());

            $cslNames[$i] = $cslNameObj;
        }

        return $cslNames;
    }

    /**
     * @param $styleName
     *
     * @return string
     * @throws FileNotFoundException
     */
    public static function loadStylesheet($styleName): ?string
    {
        include_once __DIR__ . '/../../vendorPath.php';

        if (!($vendorPath = vendorPath())) {
            throw new FileNotFoundException('Error: vendor path not found. Use composer to initialize your project');
        }

        $fileName = $vendorPath . '/academicpuma/citation-styles/' . $styleName . '.csl';

        if (!file_exists($fileName)) {
            throw new FileNotFoundException('CSL Stylesheet "' . $styleName . '" could not be found in ' . $fileName . '. A Composer update might solve this problem.');
        }

        return file_get_contents($fileName);
    }

    /**
     * @param $lang
     *
     * @return string
     * @throws FileNotFoundException
     */
    public static function loadLocale($lang): ?string
    {
        include_once __DIR__ . '/../../vendorPath.php';

        if (!($vendorPath = vendorPath())) {
            throw new FileNotFoundException('Error: vendor path not found. Use composer to initialize your project');
        }

        if (isset(self::LANG_BASES[$lang])) {
            $fileName = $vendorPath . '/academicpuma/citation-locales/locales-' . self::LANG_BASES[$lang] . '.xml';
        } else {
            $fileName = $vendorPath . '/academicpuma/citation-locales/locales-en-US.xml';
        }

        if (!file_exists($fileName)) {
            throw new FileNotFoundException('CSL Locale "' . $lang . '" could not be found in ' . $fileName . '. A Composer update might solve this problem.');
        }

        return file_get_contents($fileName);
    }

    /**
     * @param $month
     * @return string if provided month is valid, else null
     */
    public static function convertMonth($month): ?string
    {
        // check, if month is empty
        if (empty($month)) {
            return null;
        }

        // check, if month is already numeric
        if (is_numeric($month)) {
            // check, if valid month number
            if (intval($month) > 0 & intval($month) <= 12) {
                return "" . $month;
            }
            return null;
        }

        // try to convert month to numeric
        $stt = strtotime($month);
        if ($stt) {
            return date('m', $stt);
        }

        return null;
    }
}
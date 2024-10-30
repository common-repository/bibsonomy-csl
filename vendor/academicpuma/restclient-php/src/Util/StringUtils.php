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

/**
 * StringUtils contains a set of static methods to operate with strings, needed
 * to serialize/normalize person names, titles, years and so on.
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class StringUtils
{

    const WHITE_SPACE = '!\s+!';

    const AT_LEAST_TWO_WHITE_SPACES = '!\s\s+!';

    const SINGLE_NUMBER = '!\b\d+\b!';

    const FOUR_NUMBERS = '!.*(\d{4}).*!';

    const NON_NUMBERS = "![^0-9]+!";

    const NON_NUMBERS_OR_LETTERS = '/[^0-9\p{L}]+/u';

    const NON_NUMBERS_OR_LETTERS_OR_DOTS_OR_SPACE = '/[^0-9\p{L}\. ]+/u';

    const NON_NUMBERS_OR_LETTERS_OR_DOTS_OR_COMMA_OR_SPACE = '/[^0-9\p{L}\., ]+/u';

    const NON_LETTERS_OR_DOTS_OR_COMMA_OR_SEMICOLON_OR_SPACE = '/[^\p{L}\.,;\s]+/u';

    const TITLE_SOURCE_SPLIT_PATTERN = '/^(.+),\s?(\d{4}),\s?Vol\.\s?(\d+),\s?Issue\s?(\d+),\s?p\.\s?(\d+)$/';

    const DEFAULT_CHARSET = "UTF-8";

    const PARSE_MODE_KEY = "key";

    const PARSE_MODE_VALUE = "value";


    /**
     * Removes everything which is neither a number nor a letter.
     *
     * @param string|null $string $string
     * @return string result
     */
    public static function removeNonNumbersOrLetters(?string $string): ?string
    {
        return preg_replace(self::NON_NUMBERS_OR_LETTERS, "", $string);
    }

    /**
     * Removes everything, but numbers.
     *
     * @param string|null $string $string
     * @return string result
     */
    public static function removeNonNumbers(?string $string): ?string
    {
        return preg_replace(self::NON_NUMBERS, "", $string);
    }

    /**
     * @param string|null $haystack
     * @param string $needle
     * @return bool
     */
    public static function startsWith(?string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param string|null $haystack
     * @param string $needle
     * @return bool
     */
    public static function endsWith(?string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    /**
     * All strings in the array are concatenated and returned as one single
     * string, i.e. like <code>[item1,item2,item3,...]</code>.
     *
     * @param array|Collection\ArrayList $array a collection of strings to be concatenated
     *
     * @return string, i.e. like
     *         <code>[item1,item2,item3,...]</code>.
     */
    public static function getStringFromList($array): ?string
    {
        if (!is_array($array) && !($array instanceof Collection\ArrayList)) {
            return "[]";
        } else
            return "[" . implode(",", ($array instanceof Collection\ArrayList) ? $array->toArray() : $array) . "]";
    }

    public static function removeNonNumbersOrLettersOrDotsOrSpace($string)
    {
        return preg_replace(self::NON_NUMBERS_OR_LETTERS_OR_DOTS_OR_SPACE, "", self::normalizeWhitespace($string));

    }

    public static function normalizeWhitespace($string)
    {
        return preg_replace(self::WHITE_SPACE, " ", $string);
    }

    /**
     * Removes everything which is neither a number nor a letter nor a dot (.)
     * nor a comma nor nor space.
     *
     * Note: does not remove whitespace around the numbers!
     *
     * @param string|null $string $string source string
     * @return array|string|string[]|null
     */
    public static function removeNonNumbersOrLettersOrDotsOrCommaOrSpace(?string $string)
    {
        return preg_replace(self::NON_NUMBERS_OR_LETTERS_OR_DOTS_OR_COMMA_OR_SPACE, "", $string);
    }

    /**
     * Removes everything which is neither a letter nor a dot (.) nor a comma nor
     * a semicolon nor white space.
     * @param string|null $string $string
     * @return array|string|string[]|null
     */
    public static function removeNonLettersOrDotsOrCommaOrSemicolonOrSpace(?string $string)
    {
        return preg_replace(self::NON_LETTERS_OR_DOTS_OR_COMMA_OR_SEMICOLON_OR_SPACE, "", $string);
    }

    /**
     * two or more spaces in a row will be replaced by a single space character.
     *
     * @param string|null $title
     * @return string
     */
    public static function cleanTitle(?string $title): ?string
    {
        $title2 = strip_tags($title);
        return trim(preg_replace(self::AT_LEAST_TWO_WHITE_SPACES, " ", $title2));
    }

    /**
     * decodes html entities, removes tags, converts to utf8 and removes double,
     * triple (a.s.o.) white spaces
     *
     * @param string|null $title
     * @return string
     */
    public static function cleanTitle2(?string $title): ?string
    {
        $title = html_entity_decode($title);
        return self::cleanTitle(self::utf8_encode($title));
    }

    /**
     *
     * @param string|null $string $string
     * @param string $pattern
     * @return array|boolean
     */
    public static function split(?string $string, string $pattern)
    {
        return preg_split($pattern, $string);
    }

    /**
     * Returns the year from an string containing substring like: JAN 19, 2013
     * @param string|null $titleSource
     * @return string
     */
    public static function extractDateYearFromTitleSource(?string $titleSource): ?string
    {
        $matches = [];

        if (preg_match("!.*[A-Za-z]{3} \d{1,2}, (\d{4}).*!", $titleSource, $matches)) {
            return $matches[1];
        }
        return "";
    }

    /**
     * Extracts four digits (year) from a string.
     * If a year pattern will be found it returns them, otherwise an empty string.
     *
     * @param string|null $string $string
     * @return string
     */
    public static function extractYear(?string $string): ?string
    {
        $matches = [];

        if (preg_match(self::FOUR_NUMBERS, $string, $matches)) {
            return $matches[1];
        }
        return "";
    }

    /**
     *
     * @param string|null $titleSource
     * @return array
     * @throws \Exception
     */
    public static function splitTitleSource(?string $titleSource): array
    {
        $matches = [];
        if (!preg_match(self::TITLE_SOURCE_SPLIT_PATTERN, $titleSource, $matches)) {
            throw new \Exception("Pattern not found!");
        }
        return $matches;
    }

    /**
     *
     * @param string|null $titleSource
     * @return string
     * @throws \Exception
     */
    public static function extractJournalTitle(?string $titleSource): ?string
    {
        $array = self::splitTitleSource($titleSource);
        return $array[1];
    }

    /**
     *
     * @param string|null $titleSource
     * @return string
     * @throws \Exception
     */
    public static function extractYearFromTitleSource(?string $titleSource): ?string
    {
        $matches = self::splitTitleSource($titleSource);
        return $matches[2];
    }

    /**
     *
     * @param string|null $titleSource
     * @return string
     * @throws \Exception
     */
    public static function extractVolume(?string $titleSource): ?string
    {
        $array = self::splitTitleSource($titleSource);
        return $array[3];
    }

    /**
     *
     * @param string|null $titleSource
     * @return string
     * @throws \Exception
     */
    public static function extractIssue(?string $titleSource): ?string
    {
        $array = self::splitTitleSource($titleSource);
        return $array[4];
    }

    /**
     *
     * @param string|null $titleSource
     * @return string
     * @throws \Exception
     */
    public static function extractPage(?string $titleSource): ?string
    {
        $array = self::splitTitleSource($titleSource);
        return $array[5];
    }

    /**
     * Converts an array of objects to an array of strings.
     * ATTENTION: The __toString() method has to be implemented in ALL objects,
     * which the array contains.
     *
     * @param array|null $array $array
     * @return array
     */
    public static function toStringArray(?array $array): array
    {
        $retArray = [];
        foreach ($array as $item) {
            $retArray[] = "$item";
        }
        return $retArray;
    }


    public static function toASCII(?string $str): ?string
    {
        $keys = [];
        $values = [];
        $from = 'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ';
        $to = 'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy';
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($str, $mapping);

    }

    /**
     * source: http://stackoverflow.com/questions/24504331/how-to-compare-two-strings-case-and-diacritic-insensitive
     *
     * The toASCII method above doesn't work e.g. with the name Ďuranová.
     *
     * @param string|null $txt
     * @return mixed
     */
    public static function transliterateString(?string $txt)
    {
        $transliterationTable = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a',
            'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A',
            'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c',
            'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D',
            'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e',
            'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E',
            'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f',
            'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G',
            'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i',
            'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I',
            'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l',
            'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N',
            'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o',
            'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O',
            'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r',
            'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S',
            'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T',
            'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u',
            'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U',
            'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u',
            'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W',
            'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y',
            'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th',
            'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g',
            'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'e', 'ё' => 'e', 'Ё' => 'e', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z',
            'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l',
            'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r',
            'Р' => 'r', 'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f',
            'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch',
            'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e',
            'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja');
        return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
    }

    public static function md5utf8($string): ?string
    {
        $s = self::utf8_encode($string);
        return md5($s);
    }

    public static function utf8_encode($str)
    {

        $str_enc = mb_detect_encoding($str);
        $str_utf8 = $str_enc !== self::DEFAULT_CHARSET ? mb_convert_encoding($str, self::DEFAULT_CHARSET) : $str;
        return $str_utf8;
    }

    public static function parseBracketedKeyValuePairs($input, $assignmentOperator, $pairDelimiter, $bracketOpen, $bracketClosed): ?array
    {
        $keyValPairs = [];
        if (empty($input)) {
            return $keyValPairs;
        }

        $currentKey = "";
        $currentVal = "";

        $bracketDiff = 0;

        $parseMode = self::PARSE_MODE_KEY;

        for ($i = 0; $i < strlen($input); ++$i) {
            $c = substr($input, $i, 1);

            if ($parseMode === self::PARSE_MODE_VALUE && $c === $bracketOpen) {
                ++$bracketDiff;
                if ($bracketDiff === 1)
                    continue;
            }

            if ($parseMode === self::PARSE_MODE_VALUE && $c === $bracketClosed) {
                --$bracketDiff;
                if ($bracketDiff === 0)
                    continue;
            }

            if ($c === $assignmentOperator) {
                $parseMode = self::PARSE_MODE_VALUE;
                continue;
            }

            if (($c === $pairDelimiter) && ($bracketDiff === 0)) {
                self::addKeyValue($keyValPairs, $currentKey, $currentVal);
                $currentKey = "";
                $currentVal = "";
                $parseMode = self::PARSE_MODE_KEY;
                continue;
            }

            if ($parseMode === self::PARSE_MODE_KEY) {
                $currentKey .= $c;
            }

            if ($parseMode === self::PARSE_MODE_VALUE && $bracketDiff > 0) {
                $currentVal .= $c;
            }

        }
        self::addKeyValue($keyValPairs, $currentKey, $currentVal);
        return $keyValPairs;
    }

    private static function addKeyValue(?array &$keyValPairs, $currentKey, $currentVal)
    {
        $key = trim($currentKey);
        $val = trim($currentVal);

        if (!empty($key) && !empty($val)) {
            $keyValPairs[$key] = $val;
        }
    }

    /**
     *
     * Replaces new lines by space and also replaces space characters which are longer than one by a single space
     *
     * @param $value
     *
     * @return array|string|string[]|null
     */
    public static function clean($value)
    {
        $value = trim($value);
        $value = preg_replace("/\n/", " ", $value);
        $value = preg_replace("/\s+/", " ", $value);
        return $value;
    }

    /**
     * @param string|null $str
     * @return bool
     */
    public static function isNullOrEmptyString(?string $str): bool
    {
        return ($str === null || trim($str) === '');
    }
}

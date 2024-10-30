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

namespace AcademicPuma\RestClient\Util\Tex;

use AcademicPuma\RestClient\Config\ModelUtils;

/**
 * Decoder for Tex Macros
 *
 * @since  19/10/15
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class TexDecode
{

    /**
     * macros of type \"{a}
     */
    const NORMALIZE_MACRO_PATTERN = '/\\\(["^`\'])\{([A-Za-z])\}/';

    // converts \"a to {\"a}
    const NORMALIZE_VOWEL_PATTERN = '/\\\(["`^\'~])([A-Za-z])/';

    /**
     * file holding the mapping between latex macros and unicode codes
     */
    const LATEXMACRO_UNICODECHAR_MAP_FILENAME = "latex_macro_unicode_char_map.tsv";

    const CURLY_BRACKETS = "/[{}]*/";
    //Limiter dont work for lookbehind in regex, temporary fix
    const CB_OUTSIDE_MATH_MODE = '/(?<!\\\\\S\S)(?<!\\\\\S\S\S)(?<!\\\\\S\S\S\S)(?<!\\\\\S\S\S\S\S)(?<!\\\\\S\S\S\S\S\S)(?<!\\\\\S\S\S\S\S\S\S)[{}](?![^(\(|\[|$)\s]*(\)|\]|\$))/';
    //const CB_OUTSIDE_MATH_MODE = '/(?<!\\\\[\S]*)[{}](?![^(\(|\[|$)\s]*(\)|\]|\$))/';

    //const CB_OUTSIDE_MATH_MODE = '/[{}](?![^$\s]*\$)/'; // negative lookahead
    //const CB_OUTSIDE_INLINE_MATH = '/[{}](?![^\(\s]*\))/';
    //const CB_OUTSIDE_DISPLAY_MATH = '/[{}](?![^\[\s]*\])/';
    const BACKSLASHES = "/[\\\\]*/";
    /**
     * Capture backslashes with 3 negative lookahead group
     * 1. Backslashes followed directly by a special character, e.g. \( \) for MathJax-enviorment
     * 2. Commands with parameters, e.g. \emph{O}
     * 3. Lastly, if none of above matches, check ahead until closing MathJax-enviorment \)
     *     and ignore, if opening \( is found before-hand, which means the backslash was
     *     outside the Math-Jax enviorment
     */
    const BS_OUTSIDE_MATH_MODE = '/\\\\(?![\(|\)])(?![^\s]*({|\())(?![^\(]*(\)))/';
    const MATH_DOLLAR_MATCH = '/\$.*?\$/';
    const MATH_BRACKET_MATCH = '/\\\\(.*?\\\\)/';
    const BRACKETS = "/[\[\]]*/";

    private $texMap;

    /**
     * @param $content
     *
     * @return mixed
     */
    public static function convertBibtexSpecialChars($content)
    {

        /*
         * convert macros from \"{a} to {\"a}
         */
        $content = preg_replace(self::NORMALIZE_MACRO_PATTERN, '{\\\${1}${2}}', $content);

        // convert \'a | \"a etc to {\'a}
        $content = preg_replace(self::NORMALIZE_VOWEL_PATTERN, '{\\\${1}${2}}', $content);

        // Mapping of UTF-8 characters to tex macros
        $bibtex_special_chars['ä'] = '{\"a}';
        $bibtex_special_chars['ë'] = '{\"e}';
        $bibtex_special_chars['ï'] = '{\"i}';
        $bibtex_special_chars['ö'] = '{\"o}';
        $bibtex_special_chars['ü'] = '{\"u}';
        $bibtex_special_chars['Ä'] = '{\"A}';
        $bibtex_special_chars['Ë'] = '{\"E}';
        $bibtex_special_chars['Ï'] = '{\"I}';
        $bibtex_special_chars['Ö'] = '{\"O}';
        $bibtex_special_chars['Ü'] = '{\"U}';
        $bibtex_special_chars['â'] = '{\^a}';
        $bibtex_special_chars['ê'] = '{\^e}';
        $bibtex_special_chars['î'] = '{\^i}';
        $bibtex_special_chars['ô'] = '{\^o}';
        $bibtex_special_chars['û'] = '{\^u}';
        $bibtex_special_chars['Â'] = '{\^A}';
        $bibtex_special_chars['Ê'] = '{\^E}';
        $bibtex_special_chars['Î'] = '{\^I}';
        $bibtex_special_chars['Ô'] = '{\^O}';
        $bibtex_special_chars['Û'] = '{\^U}';
        $bibtex_special_chars['à'] = '{\`a}';
        $bibtex_special_chars['è'] = '{\`e}';
        $bibtex_special_chars['ì'] = '{\`i}';
        $bibtex_special_chars['ò'] = '{\`o}';
        $bibtex_special_chars['ù'] = '{\`u}';
        $bibtex_special_chars['À'] = '{\`A}';
        $bibtex_special_chars['È'] = '{\`E}';
        $bibtex_special_chars['Ì'] = '{\`I}';
        $bibtex_special_chars['Ò'] = '{\`O}';
        $bibtex_special_chars['Ù'] = '{\`U}';
        $bibtex_special_chars['á'] = '{\\\'a}';
        $bibtex_special_chars['é'] = '{\\\'e}';
        $bibtex_special_chars['í'] = '{\\\'i}';
        $bibtex_special_chars['ó'] = '{\\\'o}';
        $bibtex_special_chars['ú'] = '{\\\'u}';
        $bibtex_special_chars['Á'] = '{\\\'A}';
        $bibtex_special_chars['É'] = '{\\\'E}';
        $bibtex_special_chars['Í'] = '{\\\'I}';
        $bibtex_special_chars['Ó'] = '{\\\'O}';
        $bibtex_special_chars['Ú'] = '{\\\'U}';

        $bibtex_special_chars['ǎ'] = '{\v{a}}';
        $bibtex_special_chars['ě'] = '{\v{e}}';
        $bibtex_special_chars['ǐ'] = '{\v{i}}';
        $bibtex_special_chars['ǒ'] = '{\v{o}}';
        $bibtex_special_chars['ǔ'] = '{\v{u}}';
        $bibtex_special_chars['Ǎ'] = '{\v{A}}';
        $bibtex_special_chars['Ě'] = '{\v{E}}';
        $bibtex_special_chars['Ǐ'] = '{\v{I}}';
        $bibtex_special_chars['Ǒ'] = '{\v{O}}';
        $bibtex_special_chars['Ǔ'] = '{\v{U}}';

        $bibtex_special_chars['ç'] = '{\c{c}}';
        $bibtex_special_chars['Ç'] = '{\c{C}}';

        $bibtex_special_chars['ñ'] = '{\~n}';
        $bibtex_special_chars['Ñ'] = '{\~N}';
        $bibtex_special_chars['ń'] = '{\\\'n}';
        $bibtex_special_chars['Ń'] = '{\\\'N}';
        $bibtex_special_chars['ň'] = '{\v{n}}';
        $bibtex_special_chars['Ň'] = '{\v{N}}';

        $bibtex_special_chars['ş'] = '{\c{s}}';
        $bibtex_special_chars['Ş'] = '{\c{S}}';
        $bibtex_special_chars['š'] = '{\v{s}}';
        $bibtex_special_chars['Š'] = '{\v{S}}';
        $bibtex_special_chars['ß'] = '{\ss}';

        return str_replace($bibtex_special_chars, array_keys($bibtex_special_chars), $content);
    }

    public static function hexUTF16ToString($str)
    {

        $str = str_replace("00", "%u00", $str);
        $str = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str));

        return html_entity_decode($str, null, 'UTF-8');
    }

    private function readMap()
    {

        $fp = fopen(__DIR__ . "/" . self::LATEXMACRO_UNICODECHAR_MAP_FILENAME, 'r');

        while (!feof($fp)) {
            $line = fgets($fp, 2048);
            $delimiter = "\t";
            $data = str_getcsv($line, $delimiter);

            $this->texMap[trim($data[1])] = trim($data[0]);
        }

        fclose($fp);
    }

    /**
     * On default curly brackets will be removed as the other options where implemented later and removing was the
     * default before.
     *
     * @param string $string string to decode.
     * @param integer $treatCurlyBraces Determines how to treat curly braces in title, abstract and author field.
     * @param integer $treatBackslashes Determines how to treat backslashes in title, abstract and author field.
     * @param boolean $bibTexCleaning Determines whether BibTex cleaning will be executed.
     * @return string decoded string
     */
    public function decode($string, $treatCurlyBraces = ModelUtils::CB_REMOVE, $treatBackslashes = ModelUtils::BS_KEEP, $bibTexCleaning = true)
    {

        if (!empty($string)) {

            if ($bibTexCleaning) {
                $string = self::convertBibtexSpecialChars($string);
            }

            // Choose regular expression according to how curly braces are supposed to be treated.
            switch ($treatCurlyBraces) {

                case ModelUtils::CB_REMOVE:
                    $string = preg_replace(self::CURLY_BRACKETS, "", $string);
                    break;
                case ModelUtils::CB_KEEP:
                    break;
                default:
                    $string = preg_replace(self::CB_OUTSIDE_MATH_MODE, "", $string);
                    break;
            }

            // Choose regular expression according to how curly braces are supposed to be treated.
            switch ($treatBackslashes) {
                case ModelUtils::BS_REMOVE:
                    $string = preg_replace(self::BACKSLASHES, "", $string);
                    break;
                case ModelUtils::BS_KEEP:
                    break;
                default:
                    if (substr_count($string, '$') >= 2) {
                        // Convert $...$ math environments to \(...\)
                        $string = preg_replace(self::MATH_DOLLAR_MATCH, '\\\\(\0\\\\)', $string);
                    }
                    $string = preg_replace(self::BS_OUTSIDE_MATH_MODE, "", $string);
                    break;
            }
        }
        return $string;
    }

}
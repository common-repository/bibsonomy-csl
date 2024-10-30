<?php
/*
 *
 * restclient-php is a full-featured REST client  written in PHP 
 * for PUMA and/or BibSonomy.
 * Copyright (C) 2015, Sebastian Böttger
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


class TestHelper
{
    private static string $TESTDATA_DIR = PROJECT_HOME . '/tests/data/';

    public static function loadDocument($fileName)
    {
        return file_get_contents(self::$TESTDATA_DIR . 'documents/' . $fileName);
    }

    public static function loadXML($fileName)
    {
        return file_get_contents(self::$TESTDATA_DIR . 'xml/' . $fileName);
    }

    public static function loadCSL($fileName)
    {
        return file_get_contents(self::$TESTDATA_DIR . 'csl/' . $fileName);
    }

    public static function loadBibTeX($fileName)
    {
        return file_get_contents(self::$TESTDATA_DIR . 'bibtex/' . $fileName);
    }

    public static function loadEndnote($fileName)
    {
        return file_get_contents(self::$TESTDATA_DIR . 'endnote/' . $fileName);
    }

    public static function loadCitation($fileName)
    {
        return file_get_contents(self::$TESTDATA_DIR . 'citation/' . $fileName);
    }
}
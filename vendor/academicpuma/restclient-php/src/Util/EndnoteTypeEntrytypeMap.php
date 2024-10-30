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

use AcademicPuma\RestClient\Config\Entrytype;


/**
 * Class EndnoteTypeEntytypeMap
 *
 * @package AcademicPuma\RestClient\Util
 * @since 15.07.15
 *
 * @author Sebastian Böttger / boettger@cs.uni-kassel.de
 */
class EndnoteTypeEntrytypeMap extends EntrytypeMapper
{

    public function __construct()
    {

        $this->default = "Generic";
    }

    protected $typeMap = [
        Entrytype::ARTICLE => "Journal Article",
        Entrytype::BOOK => "Book",
        Entrytype::BOOKLET => "Book",
        Entrytype::INBOOK => "Book Section",
        Entrytype::INCOLLECTION => "Book Section",
        Entrytype::INPROCEEDINGS => "Conference Paper",
        Entrytype::MASTERTHESIS => "Thesis",
        Entrytype::PHDTHESIS => "Thesis",
        Entrytype::PROCEEDINGS => "Conference Proceedings",
        Entrytype::TECHREPORT => "Report",
        Entrytype::UNPUBLISHED => "Unpublished Work"
    ];
}
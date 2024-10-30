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

/**
 * Class CSLTypeEntytypeMap
 *
 * @package AcademicPuma\RestClient\Util
 */
class CSLTypeEntrytypeMap extends EntrytypeMapper
{

    public function __construct()
    {
        $this->default = "misc";
    }

    protected $typeMap = [

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

}
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

namespace AcademicPuma\RestClient\Config;

/**
 * Contains constant values of all resource types.
 *
 * @author Florian Fassing <florian.fassing@gmail.com>
 */
class Resourcetype
{

    /**
     * A book, booklet, manual etc..
     *
     * @var string
     */
    const BIBTEX = 'Bibtex';

    /**
     * Some URL.
     *
     * @var string
     */
    const BOOKMARK = 'Bookmark';

    /**
     * Gold standard publication
     *
     * @var string
     */
    const GOLD_STANDARD_PUBLICATION = 'GoldStandardPublication';

    /**
     * Gold standard bookmark
     *
     * @var string
     */
    const GOLD_STANDARD_BOOKMARK = 'GoldStandardBookmark';

}
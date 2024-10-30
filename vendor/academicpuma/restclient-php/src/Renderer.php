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

namespace AcademicPuma\RestClient;


use stdClass;

interface Renderer
{

    /**
     * Model Object representation of the requested API Result.
     *
     * @return Model\ModelObject
     */
    public function model();

    /**
     *
     * XML Representation of the requested API Result.
     *
     * @return string
     * @throws Exceptions\UnsupportedOperationException
     */
    public function xml();


    /**
     *
     * CSL Representation of the requested API Result.
     *
     * @return stdClass
     * @throws Exceptions\UnsupportedOperationException
     */
    public function csl();


    /**
     *
     * HTML rendered publication list in given CSL Style of the requested API Result.
     *
     * @param string $bibliographyStyle csl style name or XML formatted CSL Style
     * @param string $lang
     * @return string
     */
    public function bibliography($bibliographyStyle = 'apa', $lang = 'en-US');

    /**
     *
     * BibTeX representation of the requested API Result.
     *
     * @return string
     * @throws Exceptions\UnsupportedOperationException
     */
    public function bibtex();

    /**
     *
     * Endnote representation of the requested API Result.
     *
     * @return string
     * @throws Exceptions\UnsupportedOperationException
     */
    public function endnote();
}
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
 * Contains constant values of all BibTeX Entry types.
 *
 * @author Sebastian Böttger <boettger@cs.uni-kassel.de>
 */
class Entrytype
{

    /**
     * An article from a journal or magazine.
     * Required fields: author, title, journal, year
     * Optional fields: volume, number, pages, month, note, key
     * @var string
     */
    const ARTICLE = 'article';

    /**
     * A book with an explicit publisher.
     * Required fields: author/editor, title, publisher, year
     * Optional fields: volume/number, series, address, edition, month, note, key
     * @var string
     */
    const BOOK = 'book';

    /**
     * A work that is printed and bound, but without a named publisher or sponsoring institution.
     * Required fields: title
     * Optional fields: author, howpublished, address, month, year, note, key
     * @var string
     */
    const BOOKLET = 'booklet';

    const COLLECTION = 'collection';

    /**
     * The same as inproceedings, included for Scribe compatibility.
     * @var string
     */
    const CONFERENCE = 'conference';

    const DATASET = 'dataset';

    const ELECTRONIC = 'electronic';

    /**
     * A part of a book, usually untitled. May be a chapter (or section or whatever) and/or a range of pages.
     * Required fields: author/editor, title, chapter/pages, publisher, year
     * Optional fields: volume/number, series, type, address, edition, month, note, key
     * @var string
     */
    const INBOOK = 'inbook';

    /**
     * A part of a book having its own title.
     * Required fields: author, title, booktitle, publisher, year
     * Optional fields: editor, volume/number, series, type, chapter, pages, address, edition, month, note, key
     * @var string
     */
    const INCOLLECTION = 'incollection';

    /**
     * An article in a conference proceedings.
     * Required fields: author, title, booktitle, year
     * Optional fields: editor, volume/number, series, pages, address, month, organization, publisher, note, key
     * @var string
     */
    const INPROCEEDINGS = 'inproceedings';

    /**
     * Technical documentation.
     * Required fields: title
     * Optional fields: author, organization, address, edition, month, year, note, key
     * @var string
     */
    const MANUAL = 'manual';

    /**
     * A Master's thesis.
     * Required fields: author, title, school, year
     * Optional fields: type, address, month, note, key
     * @var string
     */
    const MASTERTHESIS = 'mastersthesis';

    /**
     * For use when nothing else fits.
     * Required fields: none
     * Optional fields: author, title, howpublished, month, year, note, key
     * @var string
     */
    const MISC = 'misc';

    const PATENT = 'patent';

    const PERIODICAL = 'periodical';

    /**
     * A Ph.D. thesis.
     * Required fields: author, title, school, year
     * Optional fields: type, address, month, note, key
     * @var string
     */
    const PHDTHESIS = 'phdthesis';

    const PREAMBLE = 'preamble';

    const PREPRINT = 'preprint';

    const PRESENTATION = 'presentation';

    /**
     * The proceedings of a conference.
     * Required fields: title, year
     * Optional fields: editor, volume/number, series, address, month, publisher, organization, note, key
     * @var string
     */
    const PROCEEDINGS = 'proceedings';

    const STANDARD = 'standard';

    /**
     * A report published by a school or other institution, usually numbered within a series.
     * Required fields: author, title, institution, year
     * Optional fields: type, number, address, month, note, key
     * @var string
     */
    const TECHREPORT = 'techreport';

    /**
     * A document having an author and title, but not formally published.
     * Required fields: author, title, note
     * Optional fields: month, year, key
     * @var string
     */
    const UNPUBLISHED = 'unpublished';
}

